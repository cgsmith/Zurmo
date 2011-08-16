<?php
    /*********************************************************************************
     * Zurmo is a customer relationship management program developed by
     * Zurmo, Inc. Copyright (C) 2011 Zurmo Inc.
     *
     * Zurmo is free software; you can redistribute it and/or modify it under
     * the terms of the GNU General Public License version 3 as published by the
     * Free Software Foundation with the addition of the following permission added
     * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
     * IN WHICH THE COPYRIGHT IS OWNED BY ZURMO, ZURMO DISCLAIMS THE WARRANTY
     * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
     *
     * Zurmo is distributed in the hope that it will be useful, but WITHOUT
     * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
     * FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
     * details.
     *
     * You should have received a copy of the GNU General Public License along with
     * this program; if not, see http://www.gnu.org/licenses or write to the Free
     * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
     * 02110-1301 USA.
     *
     * You can contact Zurmo, Inc. with a mailing address at 113 McHenry Road Suite 207,
     * Buffalo Grove, IL 60089, USA. or at email address contact@zurmo.com.
     ********************************************************************************/

    /**
     * Sanitizer for resolving if an attribute is required or not and whether the value is present. Override to
     * handle attributes that are relation models specifically.
     */
    class ModelNameIdRequiredSanitizerUtil extends RequiredSanitizerUtil
    {
        public static function getLinkedMappingRuleType()
        {
            return 'DefaultModelNameId';
        }

        public static function sanitizeValue($modelClassName, $attributeName, $value, $mappingRuleData)
        {
            assert('is_string($modelClassName)');
            assert('is_string($attributeName)');
            $model                  = new $modelClassName(false);
            if(!$model->isAttributeRequired($this->$attributeName))
            {
                return false;
            }
            assert('$model->isRelation($attributeName)');
            $relationModelClassName = $model->getRelationModelClassName($modelAttributeName);
            assert('$value == null || $value instanceof $relationModelClassName');
            assert('$mappingRuleData["defaultModelId"] == null || is_string($mappingRuleData["defaultModelId"])');
            if($value == null)
            {
                if($mappingRuleData['defaultModelId'] != null)
                {
                    try
                    {
                       $relationModel       = $relationModelClassName::getById($mappingRuleData['defaultModelId']);
                    }
                    catch(NotFoundException $e)
                    {
                        throw new InvalidValueToSanitizeException();
                    }
                    return $relationModel;
                }
                else
                {
                    throw new InvalidValueToSanitizeException();
                }
            }
            return $value;
        }
    }
?>