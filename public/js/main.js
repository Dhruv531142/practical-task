$("#employeeForm").validate({
    rules: {

        name: {
            required: true,
            maxlength: 255
        },

        employee_code: {
            required: true,
            maxlength: 255
        },

        department_id: {
            required: true
        },

        manager_id: {
            required: true
        },

        joining_date: {
            required: true,
            date: true
        },

        email: {
            email: true
        },

        phone: {
            digits: true,
            maxlength: 10,
            minlength: 10
        }

        // image: {
        //     required: true
        // }

    },

    submitHandler: function (form) {
        saveEmployee();
    }

});

