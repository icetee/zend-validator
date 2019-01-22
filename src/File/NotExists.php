<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Zend\Validator\File;

use Zend\Validator\Exception;
use Zend\Validator\File\ValidationPsr7Trait;

/**
 * Validator which checks if the destination file does not exist
 */
class NotExists extends Exists
{
    use ValidationPsr7Trait;

    /**
     * @const string Error constants
     */
    const DOES_EXIST = 'fileNotExistsDoesExist';

    /**
     * @var array Error message templates
     */
    protected $messageTemplates = [
        self::DOES_EXIST => "File exists",
    ];

    /**
     * Returns true if and only if the file does not exist in the set destinations
     *
     * @param  string|array $value Real file to check for existence
     * @param  array        $file  File data from \Zend\File\Transfer\Transfer (optional)
     * @return bool
     */
    public function isValid($value, $file = null)
    {
        extract($this->getFileInfoExists($value, $file));

        $check = false;
        $directories = $this->getDirectory(true);
        if (! isset($directories)) {
            $check = true;
            if (file_exists($file)) {
                $this->error(self::DOES_EXIST);
                return false;
            }
        } else {
            foreach ($directories as $directory) {
                if (! isset($directory) || '' === $directory) {
                    continue;
                }

                $check = true;
                if (file_exists($directory . DIRECTORY_SEPARATOR . $filename)) {
                    $this->error(self::DOES_EXIST);
                    return false;
                }
            }
        }

        if (! $check) {
            $this->error(self::DOES_EXIST);
            return false;
        }

        return true;
    }
}
