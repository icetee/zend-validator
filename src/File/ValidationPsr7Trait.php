<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Zend\Validator\File;

use Psr\Http\Message\UploadedFileInterface;
use Zend\Validator\Exception;

trait ValidationPsr7Trait
{
    /**
     * Returns array if the procedure is identified
     *
     * @param  string|array $value    Filename to check
     * @param  array        $file     File data from \Zend\File\Transfer\Transfer (optional)
     * @param  bool         $hasType  Return with filetype
     * @return array
     */
    protected function getFileInfo($value, $file = null, bool $hasType = false) : array
    {
        $fileInfo = [];

        if (is_string($value) && is_array($file)) {
            // Legacy Zend\Transfer API support
            $fileInfo['filename'] = $file['name'];
            $fileInfo['file']     = $file['tmp_name'];
            $fileInfo['filetype'] = $file['type'];
        } elseif (is_array($value)) {
            if (! isset($value['tmp_name']) || ! isset($value['name'])) {
                throw new Exception\InvalidArgumentException(
                    'Value array must be in $_FILES format'
                );
            }

            $fileInfo['file']     = $value['tmp_name'];
            $fileInfo['filename'] = $value['name'];
            $fileInfo['filetype'] = $value['type'];
        } elseif ($value instanceof UploadedFileInterface) {
            $fileInfo['file']     = $value->getStream()->getMetadata('uri');
            $fileInfo['filename'] = $value->getClientFilename();
            $fileInfo['filetype'] = $value->getClientMediaType();
        } else {
            $fileInfo['file']     = $value;
            $fileInfo['filename'] = basename($fileInfo['file']);
            $fileInfo['filetype'] = null;
        }

        if (! $hasType) {
            unset($fileInfo['filetype']);
        }

        return $fileInfo;
    }

    /**
     * Returns array if the procedure is identified
     *
     * @param  string|array $value    Filename to check
     * @param  array        $file     File data from \Zend\File\Transfer\Transfer (optional)
     * @param  bool         $hasType  Return with filetype
     * @return array
     */
    protected function getFileInfoExists($value, $file = null, bool $hasType = false) : array
    {
        $fileInfo = [];

        if (is_string($value) && is_array($file)) {
            // Legacy Zend\Transfer API support
            $fileInfo['filename'] = $file['name'];
            $fileInfo['file']     = $file['tmp_name'];
            $fileInfo['filetype'] = $file['type'];

            $this->setValue($fileInfo['filename']);
        } elseif (is_array($value)) {
            if (! isset($value['tmp_name']) || ! isset($value['name'])) {
                throw new Exception\InvalidArgumentException(
                    'Value array must be in $_FILES format'
                );
            }

            $fileInfo['file']     = $value['tmp_name'];
            $fileInfo['filename'] = $value['name'];
            $fileInfo['filetype'] = $value['type'];

            $this->setValue($value['name']);
        } elseif ($value instanceof UploadedFileInterface) {
            $fileInfo['file']     = $value->getStream()->getMetadata('uri');
            $fileInfo['filename'] = $value->getClientFilename();
            $fileInfo['filetype'] = $value->getClientMediaType();

            $this->setValue($fileInfo['filename']);
        } else {
            $fileInfo['file']     = $value;
            $fileInfo['filename'] = basename($fileInfo['file']);
            $fileInfo['filetype'] = null;

            $this->setValue($fileInfo['filename']);
        }

        if (! $hasType) {
            unset($fileInfo['filetype']);
        }

        return $fileInfo;
    }
}
