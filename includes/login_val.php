    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.21.0/dist/jquery.validate.min.js"></script>


    <script>

        $(document).ready(function(){

            $("#loginForm").validate({

                rules: {

                    username: {
                        required: true,
                        minlength: 5,
                        maxlength: 20
                    },

                    password: {
                        required: true,
                        minlength: 7
                    }

                },


                messages: {

                    username: {
                        required: "Username is required",
                        minlength: "Username must be at least 5 characters long",
                        maxlength: "Username cannot exceed 20 characters"
                    },

                    password: {
                        required: "Password is required",
                        minlength: "Password must be at least 7 characters long"
                    }

                },


                errorClass: "error",


                errorPlacement: function(error, element) {

                    error.insertAfter(element);

                }

            });

        });

    </script>