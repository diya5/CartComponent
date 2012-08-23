<?php

class DiscountConditionCompare
{
    /**
     *
     */
    protected $_id;

    /**
     *
     */
    protected $_op;

    /**
     *
     */
    protected $_isNot;

    /**
     *
     */
    protected $_sourceEntityType;

    //DEV NOTE: use either (left op right) OR (op conditions)
    // but cannot use both operations at the same time
    // examples:
    //         OR conditions means return true if any conditions are true in $_conditions
    //         AND conditions means return true if all conditions are true in $_conditions
    //         left AND right means return true if left and right are both true
    //         left OR right means return true if either left or right are true

    /**
    *
    */
    protected $_conditions;

    /**
     *
     */
    protected $_leftCondition;

    /**
    *
    */
    protected $_rightCondition;

    static $prefix = 'compare-';

    /**
     *
     */
    static function getKey($id)
    {
        return self::$prefix . $id;
    }

    /**
     *
     */
    public function __construct()
    {
        $this->reset();
    }

    /**
     *
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     *
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }

    /**
     *
     */
    public function toArray()
    {
        $leftData = array();
        if (is_object($this->getLeftCondition())) {
            $leftData = $this->getLeftCondition()->toArray();
        }

        $rightData = array();
        if (is_object($this->getRightCondition())) {
            $rightData = $this->getRightCondition()->toArray();
        }

        return array(
            'id'	     => $this->getId(),
            'op'         => $this->getOp(),
            'is_not'     => $this->getIsNot(),
            'left'       => $leftData,
            'right'      => $rightData,
            'conditions' => $this->getConditionsAsArray(),
        );
    }

    /**
     *
     */
    public function importJson($json, $reset = true)
    {
        if ($reset) {
            $this->reset();
        }

        $data = @ (array) json_decode($json);

        $id = isset($data['id']) ? $data['id'] : 0;
        $op = isset($data['op']) ? $data['op'] : '';
        $isNot = (bool) isset($data['is_not']) ? $data['is_not'] : false;

        $left = null;
        $right = null;

        $leftData = isset($data['left']) ? $data['left'] : null;
        $rightData = isset($data['right']) ? $data['right'] : null;
        $conditions = isset($data['conditions']) ? $data['conditions'] : array();

        if (is_array($leftData) || $leftData instanceof stdClass) {
            if (isset($leftData['op']) || isset($leftData->op)) {

                //we have DiscountConditionCompare data
                $left = new DiscountConditionCompare();
                if ($leftData instanceof stdClass) {
                    $left->importStdClass($leftData);
                } else if (is_array($leftData)) {
                    $left->importJson(json_encode($leftData));
                }
                
            } else {

                //we have DiscountCondition data
                $left = new DiscountCondition();
                if ($leftData instanceof stdClass) {
                    $left->importStdClass($leftData);
                } else if (is_array($leftData)) {
                    $left->importJson(json_encode($leftData));
                }

            }
        }

        if (is_array($rightData) || $rightData instanceof stdClass) {
            if (isset($rightData['op']) || isset($rightData->op)) {

                //we have DiscountConditionCompare data
                $right = new DiscountConditionCompare();
                if ($rightData instanceof stdClass) {
                    $right->importStdClass($rightData);
                } else if (is_array($rightData)) {
                    $right->importJson(json_encode($rightData));
                }

            } else {

                //we have DiscountCondition data
                $right = new DiscountCondition();
                if ($rightData instanceof stdClass) {
                    $right->importStdClass($rightData);
                } else if (is_array($rightData)) {
                    $right->importJson(json_encode($rightData));
                }
            }
        }

        if ((is_array($conditions) || $conditions instanceof stdClass) && count($conditions) > 0) {
            foreach($conditions as $key => $data) {
                if (is_int(strpos($key, DiscountConditionCompare::$prefix))) {

                    //we have DiscountConditionCompare data
                    $compare = new DiscountConditionCompare();
                    if ($data instanceof stdClass) {
                        $compare->importStdClass($data);
                        $this->addCondition($key, $compare);
                    } else if (is_array($data)) {
                        $compare->importJson(json_encode($data));
                        $this->addCondition($key, $compare);
                    }
                    
                } else if (is_int(strpos($key, DiscountCondition::$prefix))) {

                    //we have DiscountCondition data
                    $condition = new DiscountCondition();
                    if ($data instanceof stdClass) {
                        $condition->importStdClass($data);
                        $this->addCondition($key, $condition);
                    } else if (is_array($data)) {
                        $condition->importJson(json_encode($data));
                        $this->addCondition($key, $condition);
                    }
                }
            }
        }

        $this->setId($id)
             ->setOp($op)
             ->setIsNot($isNot)
             ->setLeftCondition($left)
             ->setRightCondition($right)
             ->setConditions($conditions)
             ;

        return $this;
    }

    /**
     *
     */
    public function importStdClass($obj, $reset = true)
    {
        if ($reset) {
            $this->reset();
        }

        $id = isset($obj->id) ? $obj->id : 0;
        $op = isset($obj->op) ? $obj->op : '';
        $isNot = (bool) isset($obj->is_not) ? $obj->is_not : false;

        $left = null;
        $right = null;

        $leftData = isset($obj->left) ? $obj->left : null;
        $rightData = isset($obj->right) ? $obj->right : null;

        $conditions = isset($obj->conditions) ? $obj->conditions : array();

        if (is_array($leftData) || $leftData instanceof stdClass) {
            if (isset($leftData['op']) || isset($leftData->op)) {

                //we have DiscountConditionCompare data
                $left = new DiscountConditionCompare();
                if ($leftData instanceof stdClass) {
                    $left->importStdClass($leftData);
                } else if (is_array($leftData)) {
                    $left->importJson(json_encode($leftData));
                }
                
            } else {

                //we have DiscountCondition data
                $left = new DiscountCondition();
                if ($leftData instanceof stdClass) {
                    $left->importStdClass($leftData);
                } else if (is_array($leftData)) {
                    $left->importJson(json_encode($leftData));
                }

            }
        }

        if (is_array($rightData) || $rightData instanceof stdClass) {
            if (isset($rightData['op']) || isset($rightData->op)) {

                //we have DiscountConditionCompare data
                $right = new DiscountConditionCompare();
                if ($rightData instanceof stdClass) {
                    $right->importStdClass($rightData);
                } else if (is_array($rightData)) {
                    $right->importJson(json_encode($rightData));
                }

            } else {

                //we have DiscountCondition data
                $right = new DiscountCondition();
                if ($rightData instanceof stdClass) {
                    $right->importStdClass($rightData);
                } else if (is_array($rightData)) {
                    $right->importJson(json_encode($rightData));
                }
            }
        }

        if ((is_array($conditions) || $conditions instanceof stdClass) && count($conditions) > 0) {
            foreach($conditions as $key => $data) {
                if (is_int(strpos($key, DiscountConditionCompare::$prefix))) {

                    //we have DiscountConditionCompare data
                    $compare = new DiscountConditionCompare();
                    if ($data instanceof stdClass) {
                        $compare->importStdClass($data);
                        $this->addCondition($compare);
                    } else if (is_array($data)) {
                        $compare->importJson(json_encode($data));
                        $this->addCondition($compare);
                    }
                    
                } else if (is_int(strpos($key, DiscountCondition::$prefix))) {

                    //we have DiscountCondition data
                    $condition = new DiscountCondition();
                    if ($data instanceof stdClass) {
                        $condition->importStdClass($data);
                        $this->addCondition($condition);
                    } else if (is_array($data)) {
                        $condition->importJson(json_encode($data));
                        $this->addCondition($condition);
                    }
                }
            }
        }

        $this->setId($id)
            ->setOp($op)
            ->setIsNot($isNot)
            ->setLeftCondition($left)
            ->setRightCondition($right)
            ->setConditions($conditions)
            ;

        return $this;
    }

    /**
     * Reset this object to default values
     *
     * @return DiscountConditionCompare
     */
    public function reset()
    {
        $this->setId(0)
            ->setOp('')
            ->setIsNot(false)
            ->setLeftCondition(null)
            ->setRightCondition(null)
            ->setConditions(array())
            ;

        return $this;
    }

    /**
     * Return the Id
     *
     * @return int|string
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Set your Id
     *
     * @param int|string
     * @return DiscountConditionCompare
     */
    public function setId($id)
    {
        $this->_id = $id;
        return $this;
    }

    /**
     * Get the logic operator eg 'or','and'
     *
     * @return string
     */
    public function getOp()
    {
        return $this->_op;
    }

    /**
     * Set the logic operator eg 'or','and'
     *
     * @param string
     * @return DiscountConditionCompare
     */
    public function setOp($op)
    {
        $this->_op = $op;
        return $this;
    }

    /**
     * Get wheter to return the opposite of the validation result
     *
     * @return bool
     */
    public function getIsNot()
    {
        return $this->_isNot;
    }

    /**
     * Set whether to return the opposite of the validation result
     *
     * @param bool
     * @return DiscountConditionCompare
     */
    public function setIsNot($isNot)
    {
        $this->_isNot = $isNot;
        return $this;
    }

    /**
     * Get the type of the source entity
     *
     * @return string
     */
    public function getSourceEntityType()
    {
        return $this->_sourceEntityType;
    }

    /**
     * Set the type of the source entity
     *
     * @param string
     * @return DiscountConditionCompare
     */
    public function setSourceEntityType($type)
    {
        $this->_sourceEntityType = $type;
        return $this;
    }

    /**
     * Getter for left condition/conditionCompare
     *
     * @return DiscountCondition|DiscountConditionCompare
     */
    public function getLeftCondition()
    {
        return $this->_leftCondition;
    }

    /**
     * Set left condition/conditionCompare
     *
     * @param DiscountCondition|DiscountConditionCompare $condition
     * @return DiscountConditionCompare|false
     */
    public function setLeftCondition($condition)
    {
        if (is_object($condition) && $condition->getSourceEntityType() != $this->getSourceEntityType()) {
            return false;
        }

        $this->_leftCondition = $condition;
        return $this;
    }

    /**
     * Getter for right condition/conditionCompare
     *
     * @return DiscountCondition|DiscountConditionCompare
     */
    public function getRightCondition()
    {
        return $this->_rightCondition;
    }

    /**
     * Set right condition/conditionCompare
     *
     * @param DiscountCondition|DiscountConditionCompare
     * @return DiscountConditionCompare|false
     */
    public function setRightCondition($condition)
    {
        if (is_object($condition) && $condition->getSourceEntityType() != $this->getSourceEntityType()) {
            return false;
        }

        $this->_rightCondition = $condition;
        return $this;
    }

    /**
     * Mutator
     *
     * @param DiscountCondition|DiscountConditionCompare
     * @return DiscountConditionCompare|false
     */
    public function addCondition($condition)
    {

        if (is_object($condition) && $condition->getSourceEntityType() != $this->getSourceEntityType()) {
            return false;
        }

        if (!$condition instanceof DiscountCondition &&
            !$condition instanceof DiscountConditionCompare) {
            return false;
        }

        $key = DiscountCondition::getKey($condition->getId());
        if ($condition instanceof DiscountConditionCompare) {
            $key = DiscountConditionCompare::getKey($condition->getId());
        }

        $this->_conditions[$key] = $condition;
        return $this;
    }

    /**
     * Retrieve condition by key
     *
     * @param string
     * @return DiscountCondtion|false
     */
    public function getCondition($key)
    {
        return isset($this->_conditions[$key]) ? $this->_conditions[$key] : false;
    }

    /**
     * Remove DiscountCondition by key
     *
     * @param string
     * @return DiscountConditionCompare
     */
    public function removeCondition($key)
    {
        if (isset($this->_conditions[$key])) {
            unset($this->_conditions[$key]);
        }
        return $this;
    }

    /**
     * Getter for conditions array
     *
     * @return array
     */
    public function getConditions()
    {
        return $this->_conditions;
    }

    /**
     *
     */
    public function setConditions($conditions = array())
    {
        $this->_conditions = $conditions;
        return $this;
    }

    /**
     *
     */
    public function getConditionsAsArray($object = null)
    {
        $conditions = array();

        if (is_null($object)) {
            $object = $this;
        }

        if (count($object->getConditions()) > 0) {

            //get linear tree
            foreach($object->getConditions() as $key => $tmpObject) {

                if (is_int(strpos($key, DiscountCondition::$prefix))) {
                    
                    $newObject = new DiscountCondition();
                    if ($tmpObject instanceof DiscountCondition) {
                        $newObject = $tmpObject;
                    } else if ($tmpObject instanceof stdClass) {
                        $newObject->importStdClass($tmpObject);
                    } else if (is_array($tmpObject)) {
                        $newObject->importJson(json_encode($tmpObject));
                    }
                    $conditions[$key] = $newObject->toArray();

                } else if (is_int(strpos($key, DiscountConditionCompare::$prefix))) {
                    
                    $newObject = new DiscountConditionCompare();
                    if ($tmpObject instanceof DiscountConditionCompare) {
                        $newObject = $tmpObject;
                    } else if ($tmpObject instanceof stdClass) {
                        $newObject->importStdClass($tmpObject);
                    } else if (is_array($tmpObject)) {
                        $newObject->importJson(json_encode($tmpObject));
                    }
                    $conditions[$key] = $newObject->getConditionsAsArray($newObject);

                }
            }

        } else if (is_object($object->getLeftCondition()) && is_object($object->getRightCondition())) {
            //get left-right tree
            $left = $object->getLeftCondition();
            $right = $object->getRightCondition();

            $tmpLeftData = $left->toArray();
            $tmpRightData = $right->toArray();

            $conditions['left'] = isset($tmpLeftData['op']) ? $this->getConditionsAsArray($left) : $left->toArray();
            $conditions['right'] = isset($tmpRightData['op']) ? $this->getConditionsAsArray($right) : $right->toArray();
        }

        return $conditions;
    }

    /**
    *
    */
    public function isValid($object)
    {

        //TODO: check sourceEntityType

        $left = $this->getLeftCondition();
        $right = $this->getRightCondition();
        $isNot = $this->getIsNot();

        switch($this->getOp()) {
            case 'and':
                if (count($this->getConditions())) {

                    foreach($this->getConditions() as $condition) {
                        if (!$object->isValidCondition($condition)) {
                            return ($isNot) ? true : false;
                        }
                    }

                    return ($isNot) ? false : true;
                } else {

                    if ($left instanceof DiscountCondition && 
                        $right instanceof DiscountCondition) {
                        
                        $result = $object->isValidCondition($left) && $object->isValidCondition($right);
                        return ($isNot) ? !$result : $result;
                    } else if ($left instanceof DiscountConditionCompare && 
                            $right instanceof DiscountCondition) {

                        $result = $this->isValid($left, $object) && $object->isValidCondition($right);
                        return ($isNot) ? !$result : $result;
                    } else if ($left instanceof DiscountCondition && 
                            $right instanceof DiscountConditionCompare) {

                        $result = $object->isValidCondition($left) && $this->isValid($right, $object);
                        return ($isNot) ? !$result : $result;
                    } else if ($left instanceof DiscountConditionCompare && 
                            $right instanceof DiscountConditionCompare) {

                        $result = $this->isValid($left, $object) && $this->isValid($right, $object);
                        return ($isNot) ? !$result : $result;
                    }
                }
                break;
            case 'or':
                if (count($this->getConditions())) {
                    foreach($this->getConditions() as $condition) {
                        if ($object->isValidCondition($condition)) {
                            return ($isNot) ? false : true;
                        }
                    }

                    return ($isNot) ? true : false;
                } else {

                    if ($left instanceof DiscountCondition && 
                        $right instanceof DiscountCondition) {
                        
                        $result = $object->isValidCondition($left) || $object->isValidCondition($right);
                        return ($isNot) ? !$result : $result;
                    } else if ($left instanceof DiscountConditionCompare && 
                            $right instanceof DiscountCondition) {

                        $result = $this->isValid($left, $object) || $object->isValidCondition($right);
                        return ($isNot) ? !$result : $result;
                    } else if ($left instanceof DiscountCondition && 
                            $right instanceof DiscountConditionCompare) {

                        $result = $object->isValidCondition($left) || $this->isValid($right, $object);
                        return ($isNot) ? !$result : $result;
                    } else if ($left instanceof DiscountConditionCompare && 
                            $right instanceof DiscountConditionCompare) {

                        $result = $this->isValid($left, $object) || $this->isValid($right, $object);
                        return ($isNot) ? !$result : $result;
                    }
                }

                break;
            default:
                //no-op
                break;
        }
        
        return ($isNot) ? true : false;
    }
}