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
     * Sanitizers that support the use of external system ids as possible values should extend this class.
     */
    abstract class ExternalSystemIdSuppportedSanitizerUtil extends SanitizerUtil
    {
        /**
         * Given an external system id and model class name, try to find the associated model if it exists. If it is
         * not found, a NotFoundException will be thrown.  Otherwise the model will be made and returned.
         * @param string $id
         * @param string $modelClassName
         */
        public static function getModelByExternalSystemIdAndModelClassName($id, $modelClassName)
        {
            assert('$id != null && is_string($id)');
            assert('is_string($modelClassName)');
            $tableName = $modelClassName::getTableName($modelClassName);
            $beans = RedBean_Plugin_Finder::where($tableName, ExternalSystemIdUtil::EXTERNAL_SYSTEM_ID_COLUMN_NAME . " = '$id'");
            assert('count($beans) <= 1');
            if (count($beans) == 0)
            {
                throw new NotFoundException();
            }
            return RedBeanModel::makeModel(end($beans), $modelClassName);
        }
    }
?>