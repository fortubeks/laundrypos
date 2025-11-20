<?php

namespace App\Constants;

class ApiConstants
{
    const SERVER_ERR_CODE = 500;
    const BAD_REQ_ERR_CODE = 400;
    const AUTH_ERR_CODE = 401;
    const GONE_ERR_CODE = 410;
    const FORBIDDEN_ERR_CODE = 403;
    const NOT_FOUND_ERR_CODE = 404;
    const VALIDATION_ERR_CODE = 422;
    const PAGE_EXPIRED = 419;
    const GOOD_REQ_CODE = 200;
    const AUTH_TOKEN_EXP = 60; // auth otp token expiry in minutes
    const OTP_DEFAULT_LENGTH = 7;

    const PAGINATION_SIZE_WEB = 50;
    const PAGINATION_SIZE_API = 20;

    const STATUS_PROCESSING = 'processing';
    const STATUS_PENDING = 'pending';
    const STATUS_REVERSAL = 'reversal';
    const STATUS_COMPLETE = 'successful';
    const STATUS_FAILED = 'failed';
    const STATUS_PAID = 'paid';
    const STATUS_APPROVED = 'approved';
    const STATUS_DECLINED = 'declined';

}
