
$(document).ready(()=>{

    jQuery('.js-validation').validate({
        ignore: [],
        errorClass: 'invalid-feedback animated fadeIn',
        errorElement: 'div',
        errorPlacement: (error, el) => {
            jQuery(el).addClass('is-invalid');
            jQuery(el).parents('.form-group').append(error);
        },
        highlight: (el) => {
            jQuery(el).parents('.form-group').find('.is-invalid').removeClass('is-invalid').addClass('is-invalid');
        },
        success: (el) => {
            jQuery(el).parents('.form-group').find('.is-invalid').removeClass('is-invalid');
            jQuery(el).remove();
        },
        rules: {
            'val-companyname': {
                required: true,
                minlength: 3
            },
            'email': {
                required: true,
                email: true
            },
            'password': {
                required: true
            },
            'val-username': {
                required: true,
                minlength: 3
            },
            'val-email': {
                required: true,
                email: true
            },
            'val-password': {
                required: true,
                minlength: 5
            },
            'val-confirm-password': {
                required: true,
                equalTo: '#val-password'
            },
            'val-website': {
                required: true,
                url: true
            },
            'val-jobnumber': {
                required: true
            }
        },
        messages: {
            'val-companyname': {
                required: 'Please enter a company name',
                minlength: 'Company name must consist of at least 3 characters'
            },
            'email': 'Please enter a valid email address',
            'password': 'Please input a password',
            'val-username': {
                required: 'Please enter a username',
                minlength: 'Your username must consist of at least 3 characters'
            },
            'val-email': 'Please enter a valid email address',
            'val-password': {
                required: 'Please provide a password',
                minlength: 'Your password must be at least 5 characters long'
            },
            'val-confirm-password': {
                required: 'Please provide a password',
                minlength: 'Your password must be at least 5 characters long',
                equalTo: 'Please enter the same password as above'
            },
            'val-website': 'Please enter your website!',
            'val-jobnumber': 'Please enter a Job Number',
        }
    });

    $("#save-profile").on("click", () => {

        if ($("#my-password").val().trim() == "") {
            alert("Please input new password.")
            return;
        }

        if ($("#my-password").val() != $("#my-confirm-password").val()) {
            alert("New password does not match");
            return;
        }

        $.post( baseUrl + "/editProfile", {
            "_token": Laravel.csrfToken,
            "oldPassword": $("#my-current-password").val(),
            "newPassword": $("#my-password").val()
        }, function (data, status) {
            if (status == "success") {

                if(data.status == true) {
                    $("#profile-modal").modal('hide');
                } else {
                    alert(data.message);
                }
            }
        });
    });

});

function onEditProfile() {
    $("#profile-modal").modal('show');
}

//Admin list control
function onAddAdmin() {
    $("#admin-modal-header").html("Add");
    $("#admin-form").attr("action", baseUrl + "/admin/add");
    $("#val-username").val('');
    $("#val-email").val('');
    $("#val-password").val('');
    $("#admin-modal").modal('show');
}

function onEditAdmin(id) {

    $.ajax({
        url: baseUrl + "/admin/get",
        type: "POST",
        data: {
            "_token": Laravel.csrfToken,
            "id": id
        },
        error: function() {

        },
        success: function (data) {
            if (data.status == true) {
                if (data.data.admin != null && data.data.admin.length > 0) {
                    let admin = data.data.admin[0];
                    $("#admin-modal-header").html("Edit");
                    $("#admin-form").attr("action", baseUrl + "/admin/edit");
                    $("#admin-form [name='admin-id']").val(id);
                    $("#val-email").val(admin["email"]);
                    $("#val-username").val(admin["name"]);
                    $("#admin-modal").modal('show');

                }
            }
        }
    });

}

function onDelAdmin(id) {

    if (confirm("Do you want delete this admin?")) {
        $.ajax({
            url: baseUrl + "/admin/del",
            type: "POST",
            data: {
                "_token": Laravel.csrfToken,
                "id": id
            },
            error: function() {

            },
            success: function (data) {
                if (data.status == true) {
                    window.location.reload();
                }
            }
        });
    }

}

//User list control
function onAddUser() {
    $("#user-modal-header").html("Add");
    $("#user-form").attr("action", baseUrl + "/user/add");
    $("#val-username").val('');
    $("#val-email").val('');
    $("#val-password").val('');
    $("#user-modal").modal('show');
}

function onEditUser(id) {
    $.ajax({
        url: baseUrl + "/user/get",
        type: "POST",
        data: {
            "_token": Laravel.csrfToken,
            "id": id
        },
        error: function() {

        },
        success: function (data) {
            if (data.status == true) {
                if (data.data.user != null && data.data.user.length > 0) {
                    let user = data.data.user[0];
                    $("#user-modal-header").html("Edit");
                    $("#user-form").attr("action", baseUrl + "/user/edit");
                    $("#user-form [name='user-id']").val(id);
                    $("#val-username").val(user["name"]);
                    $("#val-email").val(user["email"]);
                    $("#val-company").val(user["company_id"]);
                    $("#val-role").val(user["role"]);
                    $("#user-modal").modal('show');

                }
            }
        }
    });

}

function onDelUser(id) {

    if (confirm("Do you want delete this user?\nThe data related to this user(Images) will be also deleted.")) {
        $.ajax({
            url: baseUrl + "/user/del",
            type: "POST",
            data: {
                "_token": Laravel.csrfToken,
                "id": id
            },
            error: function() {

            },
            success: function (data) {
                if (data.status == true) {
                    window.location.reload();
                }
            }
        });
    }

}

//Company list control
function onAddCompany() {
    $("#company-modal-header").html("Add");
    $("#company-form").attr("action", baseUrl + "/company/add");
    $("#val-companyname").val('');
    $("#company-modal").modal('show');
}

function onEditCompany(id) {
    $.ajax({
        url: baseUrl + "/company/get",
        type: "POST",
        data: {
            "_token": Laravel.csrfToken,
            "id": id
        },
        error: function() {

        },
        success: function (data) {
            if (data.status == true) {
                if (data.data.company != null && data.data.company.length > 0) {
                    let company = data.data.company[0];
                    $("#company-modal-header").html("Edit");
                    $("#company-form").attr("action", baseUrl + "/company/edit");
                    $("#company-form [name='company-id']").val(id);
                    $("#val-companyname").val(company["name"]);
                    $("#company-modal").modal('show');

                }
            }
        }
    });

}

function onDelCompany(id) {

    if (confirm("Do you want delete this company?\nThe data related to this company(Users, Jobnumbers and Images) will be also deleted.")) {
        $.ajax({
            url: baseUrl + "/company/del",
            type: "POST",
            data: {
                "_token": Laravel.csrfToken,
                "id": id
            },
            error: function() {

            },
            success: function (data) {
                if (data.status == true) {
                    window.location.reload();
                }
            }
        });
    }
}

//Image list control
function onEditImage(id) {

    $.ajax({
        url: baseUrl + "/image/get",
        type: "POST",
        data: {
            "_token": Laravel.csrfToken,
            "id": id
        },
        error: function() {

        },
        success: function (data) {
            if (data.status == true) {
                if (data.data.image != null && data.data.image.length > 0) {
                    let image = data.data.image[0];
                    $("#image-form [name='image-id']").val(id);
                    //$("#val-email").val(image["email"]);
                    $("#image-modal").modal('show');

                }
            }
        }
    });

}

function onDelImage(id) {

    if (confirm("Do you want delete this image?")) {
        $.ajax({
            url: baseUrl + "/image/del",
            type: "POST",
            data: {
                "_token": Laravel.csrfToken,
                "id": id
            },
            error: function() {

            },
            success: function (data) {
                if (data.status == true) {
                    window.location.reload();
                }
            }
        });
    }

}


//Job number control
function onAddJobNumber() {
    $("#jobnumber-modal-header").html("Add");
    $("#jobnumber-form").attr("action", baseUrl + "/jobnumber/add");
    $("#val-jobnumber").val('');
    $("#jobnumber-modal").modal('show');
}

function onEditJobNumber(id) {

    $.ajax({
        url: baseUrl + "/jobnumber/get",
        type: "POST",
        data: {
            "_token": Laravel.csrfToken,
            "id": id
        },
        error: function() {

        },
        success: function (data) {
            if (data.status == true) {
                if (data.data.jobnumber != null && data.data.jobnumber.length > 0) {
                    let jobnumber = data.data.jobnumber[0];
                    $("#jobnumber-modal-header").html("Edit");
                    $("#jobnumber-form").attr("action", baseUrl + "/jobnumber/edit");
                    $("#jobnumber-form [name='jobnumber-id']").val(id);
                    $("#val-companyid").val(jobnumber["company_id"]);
                    $("#val-jobnumber").val(jobnumber["jobnumber"]);
                    $("#jobnumber-modal").modal('show');

                }
            }
        }
    });

}

function onDelJobNumber(id) {

    if (confirm("Do you want delete this JobNumber?\nThe data related to this Jobnumber(Images) will be also deleted.")) {
        $.ajax({
            url: baseUrl + "/jobnumber/del",
            type: "POST",
            data: {
                "_token": Laravel.csrfToken,
                "id": id
            },
            error: function() {

            },
            success: function (data) {
                if (data.status == true) {
                    window.location.reload();
                }
            }
        });
    }

}
