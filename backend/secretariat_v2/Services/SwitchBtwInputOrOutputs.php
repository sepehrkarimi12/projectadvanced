<?php

namespace app\modules\secretariat_v2\Services;

use app\modules\secretariat_v2\Interfaces\Handler;

/**
 * this service works like a switch selector
 * it looks at the type of the letter then sets the next stage depending on which type of the letter is in input
 * @author Noei
 */
class SwitchBtwInputOrOutputs extends BasicHandler implements Handler
{
    public function handleRequest($request)
    {

        switch ($request->getService()->office_type_id) {
            case 1:

                // adds one stage btw current and next stage
                $input_fields = new FillInputFields();
                $input_fields->setSuccessor($this->successor);

                $this->successor = $input_fields;

                break;
            case 2:
                /**
                 * @todo output letter
                 */
                break;
            default:
                throw new Exception('invalid type id');
        }

        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }
    }
}