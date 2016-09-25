<?php

namespace PierreLemee\MflParser\Readers\Handlers\Mfj;

use PierreLemee\MflParser\Model\GridFile;
use PierreLemee\MflParser\Readers\Handlers\AbstractHandler;

class DefinitionsHandler extends AbstractHandler
{
    const STATE_UKNOWN = 0;
    const STATE_BODY = 1;
    const STATE_DEFINITION = 2;
    const STATE_DEFINITION_PART = 3;

    protected function getKeyPattern()
    {
        return "/definitions/";
    }

    public function processEntry($key, $value, GridFile $file)
    {
        $state = self::STATE_UKNOWN;
        $index = 0;
        $definition = "";

        foreach (str_split(trim($value)) as $char) {
            switch ($char) {
                case '[':
                    if ($state === self::STATE_UKNOWN) {
                        $state = self::STATE_BODY;
                    } else if ($state === self::STATE_BODY) {
                        $state = self::STATE_DEFINITION;
                    }
                    break;
                case ']':
                    if ($state === self::STATE_BODY) {
                        $state = self::STATE_UKNOWN;
                    } else if ($state === self::STATE_DEFINITION) {
                        $file->addDefinition(trim($definition), ++$index);
                        $definition = "";
                        $state = self::STATE_BODY;
                    }
                    break;
                case '"':
                    if ($state === self::STATE_DEFINITION) {
                        $state = self::STATE_DEFINITION_PART;
                    } else if ($state === self::STATE_DEFINITION_PART) {
                        $definition .= (ord($char) !== 147 ? " " : "");
                        $state = self::STATE_DEFINITION;
                    }
                    break;
                default:
                    if ($state === self::STATE_DEFINITION_PART) {
                        $definition .= $char;
                    }
            }
        }
    }
}