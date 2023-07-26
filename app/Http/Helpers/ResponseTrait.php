<?php
/**
 * Response Trait
 */
namespace App\Http\Helpers;

trait ResponseTrait
{

    /**
     * Response - Success
     * @param type $data
     * @param type $message
     * @param type $code
     * @param type $lang
     * @return type
     */
    public function success($data = null, $message = 'SUCCESS', $code = 200, $lang = null)
    {
        if ($lang==null) {
            $lang = $this->getLanguage();
        }
        $meta = [
            'status' => true,
            'message' => trans('message.'.$message, [], $lang),
            'message_code' => $message,
            'status_code' => $code
        ];
        return response()->json(['meta' => $meta, 'data' => $data], $code);
    }

    /**
     * Response - Error
     * @param type $message
     * @param type $code
     * @return type
     */
    public function error($message = 'ERROR', $code = 422)
    {
        $lang = $this->getLanguage();
        $meta = [
            'status' => false,
            'message' => trans('error.'.$message, [], $lang),
            'message_code' => $message,
            'status_code' => $code
        ];
        return response()->json(['meta' => $meta], $code);
    }

    /**
     * Response - Server side validation error message
     * @param type $validation
     * @param type $message
     * @param type $code
     * @return type
     */
    public function validationError(
        $validation,
        $message = 'VALIDATION_ERROR',
        $code = 422
    ) {
        $fieldMessages = $validation->errors();
        $errorMsg = $fieldMessages->first();
        $meta          = [
            'status' => false,
            'message' => $errorMsg,
            'message_code' => $message,
            'status_code' => 422
        ];
        return response()->json(['meta' => $meta, 'errors' => $fieldMessages], $code);
    }
    
    /**
     * Get Locale from header
     * @return string
     */
    public function getLanguage()
    {
        $headers = apache_request_headers();
        if (isset($headers['Language']) and in_array($headers['Language'], ['en', 'fr'])) {
            return $headers['Language'];
        }
        if (isset($headers['language']) and in_array($headers['language'], ['en', 'fr'])) {
            return $headers['language'];
        }
        return 'en';
    }
    
    /**
    * Custom validation msg
    * @return type
    */
    public function customValidationMsg($msg = 'ERROR', $paramArr) {
        $lang = $this->getLanguage();
        $meta = [
            'status' => false,
            'message' => trans('error.'.$msg, $paramArr, $lang),
            'message_code' => $msg,
            'status_code' => 422
        ];
        return response()->json(['meta' => $meta], 422);
    }
}

