<?php

namespace UserBundle;

/**
 * Contains all events thrown in the UserBundle.
 * author Design studio origami <https://origami.ua>
 */
final class UserEvents
{
    /**
     * The SECURITY_IMPLICIT_LOGIN event occurs when the user is logged in programmatically.
     *
     * This event allows you to access the response which will be sent.
     *
     * @Event("UserBundle\Event\UserEvent")
     */
    const SECURITY_IMPLICIT_LOGIN = 'user.security.implicit_login';

    /**
     * The REGISTRATION_INITIALIZE event occurs when the registration process is initialized.
     *
     * This event allows you to modify the default values of the user before binding the form.
     *
     * @Event("UserBundle\Event\UserEvent")
     */
    const REGISTRATION_INITIALIZE = 'user.registration.initialize';

    /**
     * The REGISTRATION_COMPLETED event occurs after saving the user in the registration process.
     *
     * This event allows you to access the response which will be sent.
     *
     * @Event("UserBundle\Event\FilterUserResponseEvent")
     */
    const REGISTRATION_COMPLETED = 'user.registration.completed';

    /**
     * The REGISTRATION_CONFIRMED event occurs after confirming the account.
     *
     * This event allows you to access the response which will be sent.
     *
     * @Event("UserBundle\Event\FilterUserResponseEvent")
     */
    const REGISTRATION_CONFIRMED = 'user.registration.confirmed';

    /**
     * The REGISTRATION_CONFIRM event occurs just before confirming the account.
     *
     * This event allows you to access the user which will be confirmed.
     *
     * @Event("UserBundle\Event\GetResponseUserEvent")
     */
    const REGISTRATION_CONFIRM = 'user.registration.confirm';

    /**
     * The REGISTRATION_SUCCESS event occurs when the registration form is submitted successfully.
     *
     * This event allows you to set the response instead of using the default one.
     *
     * @Event("UserBundle\Event\FormEvent")
     */
    const REGISTRATION_SUCCESS = 'user.registration.success';

    /**
     * The REGISTRATION_FAILURE event occurs when the registration form is not valid.
     *
     * This event allows you to set the response instead of using the default one.
     * The event listener method receives a UserBundle\Event\FormEvent instance.
     *
     * @Event("UserBundle\Event\FormEvent")
     */
    const REGISTRATION_FAILURE = 'user.registration.failure';

    /**
     * The RESETTING_RESET_INITIALIZE event occurs when the resetting process is initialized.
     *
     * This event allows you to set the response to bypass the processing.
     *
     * @Event("UserBundle\Event\GetResponseUserEvent")
     */
    const RESETTING_RESET_INITIALIZE = 'user.resetting.reset.initialize';

    /**
     * The RESETTING_RESET_COMPLETED event occurs after saving the user in the resetting process.
     *
     * This event allows you to access the response which will be sent.
     *
     * @Event("UserBundle\Event\FilterUserResponseEvent")
     */
    const RESETTING_RESET_COMPLETED = 'user.resetting.reset.completed';

    /**
     * The RESETTING_SEND_EMAIL_INITIALIZE event occurs when the send email process is initialized.
     *
     * This event allows you to set the response to bypass the email confirmation processing.
     * The event listener method receives a UserBundle\Event\GetResponseNullableUserEvent instance.
     *
     * @Event("UserBundle\Event\GetResponseNullableUserEvent")
     */
    const RESETTING_SEND_EMAIL_INITIALIZE = 'user.resetting.send_email.initialize';

    /**
     * The RESETTING_RESET_SUCCESS event occurs when the resetting form is submitted successfully.
     *
     * This event allows you to set the response instead of using the default one.
     *
     * @Event("UserBundle\Event\FormEvent ")
     */
    const RESETTING_RESET_SUCCESS = 'user.resetting.reset.success';

    /**
     * The RESETTING_RESET_REQUEST event occurs when a user requests a password reset of the account.
     *
     * This event allows you to check if a user is locked out before requesting a password.
     * The event listener method receives a UserBundle\Event\GetResponseUserEvent instance.
     *
     * @Event("UserBundle\Event\GetResponseUserEvent")
     */
    const RESETTING_RESET_REQUEST = 'user.resetting.reset.request';

    /**
     * The RESETTING_SEND_EMAIL_CONFIRM event occurs when all prerequisites to send email are
     * confirmed and before the mail is sent.
     *
     * This event allows you to set the response to bypass the email sending.
     * The event listener method receives a UserBundle\Event\GetResponseUserEvent instance.
     *
     * @Event("UserBundle\Event\GetResponseUserEvent")
     */
    const RESETTING_SEND_EMAIL_CONFIRM = 'user.resetting.send_email.confirm';

    /**
     * The RESETTING_SEND_EMAIL_COMPLETED event occurs after the email is sent.
     *
     * This event allows you to set the response to bypass the the redirection after the email is sent.
     * The event listener method receives a UserBundle\Event\GetResponseUserEvent instance.
     *
     * @Event("UserBundle\Event\GetResponseUserEvent")
     */
    const RESETTING_SEND_EMAIL_COMPLETED = 'user.resetting.send_email.completed';

    const AUTO_REGISTRATION_SUCCESS = 'user.autoregistration.success';

    const NEW_CREATED_SUCCESS = 'user.created.success';
}
