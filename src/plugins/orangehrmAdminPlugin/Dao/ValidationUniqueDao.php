<?php
/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA 02110-1301, USA
 */

namespace OrangeHRM\Admin\Dao;

use OrangeHRM\Core\Dao\BaseDao;
use OrangeHRM\Core\Traits\LoggerTrait;

class ValidationUniqueDao extends BaseDao
{
    use LoggerTrait;

    /**
     * @param string $value
     * @param string $entityName
     * @param string $attributeName
     * @param string|null $entityId
     * @return bool
     */
    public function isValueUnique(string $value, string $entityName, string $attributeName, ?string $entityId, ?string $matchByField, ?string $matchByValue): bool
    {
        $this->getLogger()->error('values', [$value, $entityName, $attributeName, $matchByField, $matchByValue]);
        $qb = $this->createQueryBuilder('OrangeHRM\\Entity\\'  . ucfirst($entityName), 'entity');
        $qb->andWhere($qb->expr()->eq('entity.' . $attributeName, ':value'))
            ->setParameter('value', $value);

        if (!is_null($entityId)) {
            $qb->andWhere($qb->expr()->neq('entity.id', ':id'))
                ->setParameter('id', $entityId);
        }

        if (!is_null($matchByValue) && !is_null($matchByField)) {
            $qb->andWhere($qb->expr()->eq('entity.' . $matchByField, ':matchValue'))
                ->setParameter('matchValue', $matchByValue);
        }

        $results = $qb->getQuery()->execute();
        $this->getLogger()->error($qb->getQuery()->getSQL());
        $this->getLogger()->error('results', $qb->getQuery()->getArrayResult());

        return empty($results);
    }
}
