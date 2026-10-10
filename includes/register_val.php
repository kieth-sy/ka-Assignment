<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.21.0/dist/jquery.validate.min.js"></script>


    <script>

        $(document).ready(function(){

            $("#registerForm").validate({

                rules: {

                    fullname: {
                        required: true,
                        minlength: 5,
                        maxlength: 50
                    },

                    email: {
                        required: true,
                        email: true
                    },

                    username: {
                        required: true,
                        minlength: 5,
                        maxlength: 20
                    },

                    password: {
                        required: true,
                        minlength: 7
                    },

                    "confirm-password": {
                        required: true,
                        equalTo: "#register-password"
                    },

                    terms: {
                        required: true
                    }

                },


                messages: {

                    fullname: {
                        required: "Name is required",
                        minlength: "Name must be at least 5 characters long",
                        maxlength: "Name cannot exceed 50 characters"
                    },

                    email: {
                        required: "Email is required",
                        email: "Please enter a valid email address"
                    },

                    username: {
                        required: "Username is required",
                        minlength: "Username must be at least 5 characters long",
                        maxlength: "Username cannot exceed 20 characters"
                    },

                    password: {
                        required: "Password is required",
                        minlength: "Password must be at least 7 characters long"
                    },

                    "confirm-password": {
                        required: "Confirm password is required",
                        equalTo: "Confirm password must be the same as your password"
                    },

                    terms: {
                        required: "You must agree to the Terms and Conditions"
                    }

                },


                errorClass: "error",


                errorPlacement: function(error, element) {

                    if (element.attr("name") == "terms") {

                        error.insertAfter(element.closest(".terms"));

                    } else {

                        error.insertAfter(element);

                    }

                }

            });

        });

    </script>