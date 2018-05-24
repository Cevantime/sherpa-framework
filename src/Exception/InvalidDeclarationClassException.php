<?php

namespace Sherpa\Exception;

/**
 * Description of InvalidDeclarationClassException
 *
 * @author cevantime
 */
class InvalidDeclarationClassException extends \Exception
{
    public function __construct(string $className)
    {
        parent::__construct("$className does not implement " + \Sherpa\Declaration\DeclarationInterface::class + " and cannot be used as a declaration.");
    }
}
