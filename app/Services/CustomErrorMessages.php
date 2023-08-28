<?php

namespace App\Services;

class CustomErrorMessages
{
    public static function getCustomMessage($exception, $item = '')
    {
          // return $exception->getMessage();

      $errorCode = $exception->errorInfo[1];

      if ($errorCode === 1062) {
          // Duplicate entry error
          $customErrorMessage = "This record already exists.";
      } elseif ($errorCode === 1048) {
          // Column cannot be null error
          $customErrorMessage = "Please provide a value for all required fields.";
      } elseif ($errorCode === 1451) {
          // Cannot delete/update parent row error
          $customErrorMessage = "Cannot delete this record due to existing references.";
      } elseif ($errorCode === 1452) {
          // Cannot add/update child row error
          $customErrorMessage = "The provided value does not exist in the referenced table.";
      } elseif ($errorCode === 1064) {
          // Syntax error
          $customErrorMessage = "There is a syntax error in the query.";
      } elseif ($errorCode === 1364) {
          // Field doesn't have a default value error
          $customErrorMessage = "Please provide a value for this field.";
      } elseif ($errorCode === 1216) {
          // Cannot add foreign key constraint error
          $customErrorMessage = "The referenced column does not exist in the referenced table.";
      } elseif ($errorCode === 1459) {
          // Cannot delete a parent row error
          $customErrorMessage = "Cannot delete this record as it's referenced by other records.";
      } else {
          // For other, unspecified errors
          $customErrorMessage = "An error occurred while processing your request.";
      }

      return $customErrorMessage;
    }
}
