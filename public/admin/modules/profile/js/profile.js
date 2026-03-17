"use strict";

const KTProfile = function () {

    const handleDetailsForm = function () {
        const form = document.getElementById('kt_profile_details_form');
        if (!form) return;

        const submitButton = document.getElementById('kt_profile_details_submit');

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            if (submitButton) {
                submitButton.setAttribute('data-kt-indicator', 'on');
                submitButton.disabled = true;
            }

            const formData = new FormData(form);

            for (const [key, value] of formData.entries()) {
                if (value === null || value === undefined) {
                    formData.set(key, '');
                }
            }

            const photoInput = form.querySelector('input[name="photo"]');
            let photoPromise = Promise.resolve();

            if (photoInput && photoInput.files && photoInput.files.length > 0) {
                const photoData = new FormData();
                photoData.append('photo', photoInput.files[0]);

                photoPromise = axios.post('/admin/modules/profile/api/update_photo.php', photoData)
                    .then(response => {
                        if (response.data?.filepath) {
                            const avatarImg = document.getElementById('profile_avatar_img');
                            const avatarEdit = document.getElementById('profile_avatar_edit_img');

                            if (avatarImg) {
                                avatarImg.src = response.data.filepath;
                            }
                            if (avatarEdit) {
                                avatarEdit.style.backgroundImage = `url(${response.data.filepath})`;
                            }
                        }
                    })
                    .catch(error => {
                        Swal.fire(
                            'Photo Error',
                            error.response?.data?.message || 'Could not upload photo.',
                            'error'
                        );
                    });
            }

            photoPromise.finally(() => {
                axios.post('/admin/modules/profile/api/update_details.php', formData)
                    .then(response => {
                        Swal.fire('Success!', response.data.message, 'success')
                            .then(() => location.reload());
                    })
                    .catch(error => {
                        Swal.fire(
                            'Update Error',
                            error.response?.data?.message || 'Could not update details.',
                            'error'
                        );
                    })
                    .finally(() => {
                        if (submitButton) {
                            submitButton.removeAttribute('data-kt-indicator');
                            submitButton.disabled = false;
                        }
                    });
            });
        });
    };

    const handlePasswordForm = function () {
        const form = document.getElementById('kt_profile_password_form');
        if (!form) return;

        const submitButton = form.querySelector('button[type="submit"]');

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            if (submitButton) {
                submitButton.setAttribute('data-kt-indicator', 'on');
                submitButton.disabled = true;
            }

            const formData = new FormData(form);
            const payload = {};

            formData.forEach((value, key) => {
                payload[key] = value ?? '';
            });

            axios.post('/admin/modules/profile/api/update_password.php', payload)
                .then(response => {
                    Swal.fire('Success!', response.data.message, 'success');
                    form.reset();
                })
                .catch(error => {
                    Swal.fire(
                        'Error',
                        error.response?.data?.message || 'Could not change password.',
                        'error'
                    );
                })
                .finally(() => {
                    if (submitButton) {
                        submitButton.removeAttribute('data-kt-indicator');
                        submitButton.disabled = false;
                    }
                });
        });
    };

    return {
        init: function () {
            handleDetailsForm();
            handlePasswordForm();
        }
    };
}();

KTUtil.onDOMContentLoaded(function () {
    KTProfile.init();
});
