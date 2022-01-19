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
 * Boston, MA  02110-1301, USA
 */

namespace OrangeHRM\Time\Dao;

use OrangeHRM\Core\Dao\BaseDao;
use OrangeHRM\Entity\Project;
use OrangeHRM\Entity\ProjectAdmin;
use OrangeHRM\Entity\TimesheetItem;
use OrangeHRM\ORM\Paginator;
use OrangeHRM\Time\Dto\ProjectSearchFilterParams;

class ProjectDao extends BaseDao
{
    /**
     * @param Project $project
     * @return Project
     */
    public function saveProject(Project $project): Project
    {
        $this->persist($project);
        return $project;
    }

    /**
     * @param int[] $ids
     * @return int
     */
    public function deleteProjects(array $ids): int
    {
        $q = $this->createQueryBuilder(Project::class, 'project');
        $q->update()
            ->set('project.deleted', ':deleted')
            ->setParameter('deleted', true)
            ->where($q->expr()->in('project.id', ':ids'))
            ->setParameter('ids', $ids);
        return $q->getQuery()->execute();
    }

    /**
     * @param int $id
     * @return Project|null
     */
    public function getProjectById(int $id): ?Project
    {
        $qb = $this->createQueryBuilder(Project::class, 'project');
        $qb->andWhere('project.id = :id')->setParameter('id', $id);
        $qb->andWhere('project.deleted = :deleted')->setParameter('deleted', false);
        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param ProjectSearchFilterParams $projectSearchFilterParamHolder
     * @return Project[]
     */
    public function getProjects(ProjectSearchFilterParams $projectSearchFilterParamHolder): array
    {
        $qb = $this->getProjectsPaginator($projectSearchFilterParamHolder);
        return $qb->getQuery()->execute();
    }

    /**
     * @param ProjectSearchFilterParams $projectSearchFilterParamHolder
     * @return Paginator
     */
    protected function getProjectsPaginator(ProjectSearchFilterParams $projectSearchFilterParamHolder): Paginator
    {
        $qb = $this->createQueryBuilder(Project::class, 'project');
        $qb->leftJoin('project.customer', 'customer');
        $qb->leftJoin('project.projectAdmins', 'projectAdmin');

        $this->setSortingAndPaginationParams($qb, $projectSearchFilterParamHolder);

        if (!is_null($projectSearchFilterParamHolder->getProjectIds())) {
            $qb->andWhere($qb->expr()->in('project.id', ':projectIds'))
                ->setParameter('projectIds', $projectSearchFilterParamHolder->getProjectIds());
        }
        if (!is_null($projectSearchFilterParamHolder->getCustomerId())) {
            $qb->andWhere('customer.id = :customerId')
                ->setParameter('customerId', $projectSearchFilterParamHolder->getCustomerId());
        }
        if (!is_null($projectSearchFilterParamHolder->getEmpNumber())) {
            $qb->andWhere('projectAdmin.empNumber = :empNumber')
                ->setParameter('empNumber', $projectSearchFilterParamHolder->getEmpNumber());
        }
        if (!is_null($projectSearchFilterParamHolder->getCustomerOrProjectName())) {
            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('project.name', ':customerOrProjectName'),
                    $qb->expr()->like('customer.name', ':customerOrProjectName'),
                )
            );
            $qb->setParameter('customerOrProjectName', '%' . $projectSearchFilterParamHolder->getCustomerOrProjectName() . '%');
        }
        if (!empty($projectSearchFilterParamHolder->getName())) {
            $qb->andWhere($qb->expr()->like('project.name', ':projectName'))
                ->setParameter('projectName', '%' . $projectSearchFilterParamHolder->getName() . '%');
        }
        if (!empty($projectSearchFilterParamHolder->getExcludeProjectIds())) {
            $qb->andWhere($qb->expr()->notIn('project.id', ':excludeProjectIds'))
                ->setParameter('excludeProjectIds', $projectSearchFilterParamHolder->getExcludeProjectIds());
        }

        $qb->andWhere('project.deleted = :deleted')
            ->setParameter('deleted', false);
        return $this->getPaginator($qb);
    }

    /**
     * @param ProjectSearchFilterParams $projectSearchFilterParamHolder
     * @return int
     */
    public function getProjectsCount(ProjectSearchFilterParams $projectSearchFilterParamHolder): int
    {
        return $this->getProjectsPaginator($projectSearchFilterParamHolder)->count();
    }

    /**
     * @param string $projectName
     * @param int|null $projectId
     * @return bool
     */
    public function isProjectNameTaken(string $projectName, ?int $projectId = null): bool
    {
        $q = $this->createQueryBuilder(Project::class, 'project');
        $q->andWhere('project.name = :projectName');
        $q->setParameter('projectName', $projectName);
        $q->andWhere('project.deleted = :deleted');
        $q->setParameter('deleted', false);
        if (!is_null($projectId)) {
            $q->andWhere('project.id != :projectId');
            $q->setParameter('projectId', $projectId);
        }
        return $this->getPaginator($q)->count() === 0;
    }

    /**
     * @param int|null $empNumber
     * @return bool
     */
    public function isProjectAdmin(?int $empNumber): bool
    {
        if (is_null($empNumber)) {
            return false;
        }
        $q = $this->createQueryBuilder(ProjectAdmin::class, 'projectAdmin')
            ->andWhere('projectAdmin.employee = :empNumber')
            ->setParameter('empNumber', $empNumber);
        return $this->getPaginator($q)->count() > 0;
    }

    /**
     * @param bool $includeDeleted
     * @return int[]
     */
    public function getProjectIdList(bool $includeDeleted = false): array
    {
        $q = $this->createQueryBuilder(Project::class, 'project');
        $q->select('project.id');
        if (!$includeDeleted) {
            $q->andWhere('project.deleted = :deleted')
                ->setParameter('deleted', false);
        }
        $result = $q->getQuery()->getArrayResult();
        return array_column($result, 'id');
    }

    /**
     * @param int $projectAdminEmpNumber
     * @param bool $includeDeleted
     * @return int[]
     */
    public function getProjectIdListForProjectAdmin(int $projectAdminEmpNumber, bool $includeDeleted = false): array
    {
        $q = $this->createQueryBuilder(Project::class, 'project')
            ->select('project.id')
            ->innerJoin('project.projectAdmins', 'projectAdmin')
            ->andWhere('projectAdmin.empNumber = :projectAdminEmpNumber')
            ->setParameter('projectAdminEmpNumber', $projectAdminEmpNumber);
        if (!$includeDeleted) {
            $q->andWhere('project.deleted = :deleted')
                ->setParameter('deleted', false);
        }
        $result = $q->getQuery()->getArrayResult();
        return array_column($result, 'id');
    }

    /**
     * @param  int  $projectId
     * @return bool
     */
    public function hasTimesheetItemsForProject(int $projectId): bool
    {
        $qb = $this->createQueryBuilder(TimesheetItem::class, 'timesheetItem');
        $qb->andWhere('timesheetItem.project = :projectId');
        $qb->setParameter('projectId', $projectId);
        return $this->getPaginator($qb)->count() > 0;
    }

    /**
     * @return int[]
     */
    public function getUnselectableProjectIds(): array
    {
        $qb = $this->createQueryBuilder(TimesheetItem::class, 'timesheetItem');
        $qb->leftJoin('timesheetItem.project', 'project');
        $qb->select('project.id');
        $qb->addGroupBy('project.id');
        $result = $qb->getQuery()->getArrayResult();
        return array_column($result, 'id');
    }

    /**
     * @return int[]
     */
    public function getActivityIdsOfProjectInTimesheetItems($projectId): array
    {
        $qb = $this->createQueryBuilder(TimesheetItem::class, 'timesheetItem');
        $qb->leftJoin('timesheetItem.projectActivity', 'projectActivity');
        $qb->select('projectActivity.id');
        $qb->andWhere('timesheetItem.project = :projectId');
        $qb->setParameter('projectId', $projectId);
        $qb->addGroupBy('projectActivity.id');
        $result = $qb->getQuery()->getArrayResult();
        return array_column($result, 'id');
    }
}
