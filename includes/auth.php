<?php

const AUTH_USERS_FILE = __DIR__ . '/../data/users.json';

function openUsersStore(int $lockMode)
{
    $directory = dirname(AUTH_USERS_FILE);
    if (!is_dir($directory) && !mkdir($directory, 0700, true) && !is_dir($directory)) {
        throw new RuntimeException('Unable to create the account storage directory.');
    }

    $file = fopen(AUTH_USERS_FILE, 'c+');
    if ($file === false) {
        throw new RuntimeException('Unable to open the account storage file.');
    }

    if (!flock($file, $lockMode)) {
        fclose($file);
        throw new RuntimeException('Unable to lock the account storage file.');
    }

    try {
        rewind($file);
        $contents = stream_get_contents($file);
        if ($contents === false) {
            throw new RuntimeException('Unable to read the account storage file.');
        }

        $users = $contents === '' ? [] : json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        if (!is_array($users)) {
            throw new RuntimeException('The account storage file has an invalid format.');
        }
    } catch (JsonException | RuntimeException $exception) {
        flock($file, LOCK_UN);
        fclose($file);
        throw new RuntimeException('Unable to read account records.', 0, $exception);
    }

    return [$file, $users];
}

function closeUsersStore($file): void
{
    flock($file, LOCK_UN);
    fclose($file);
}

function findUserByUsername(string $username): ?array
{
    [$file, $users] = openUsersStore(LOCK_SH);
    try {
        foreach ($users as $user) {
            if (isset($user['username']) && strcasecmp($user['username'], $username) === 0) {
                return $user;
            }
        }
    } finally {
        closeUsersStore($file);
    }

    return null;
}

function createUser(array $newUser): bool
{
    [$file, $users] = openUsersStore(LOCK_EX);
    try {
        foreach ($users as $user) {
            if (
                (isset($user['username']) && strcasecmp($user['username'], $newUser['username']) === 0)
                || (isset($user['email']) && strcasecmp($user['email'], $newUser['email']) === 0)
            ) {
                return false;
            }
        }

        $users[] = $newUser;
        $json = json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
        rewind($file);
        if (!ftruncate($file, 0) || fwrite($file, $json) === false || !fflush($file)) {
            throw new RuntimeException('Unable to save the account record.');
        }
    } finally {
        closeUsersStore($file);
    }

    return true;
}
