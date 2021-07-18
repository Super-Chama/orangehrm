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

namespace OrangeHRM\Tests\Core\Authorization\Helper;

use OrangeHRM\Core\Authorization\Dto\ResourcePermission;
use OrangeHRM\Core\Authorization\Helper\UserRoleManagerHelper;
use OrangeHRM\Core\Authorization\Manager\BasicUserRoleManager;
use OrangeHRM\Entity\Employee;
use OrangeHRM\Entity\User;
use OrangeHRM\Entity\UserRole;
use OrangeHRM\Framework\Services;
use OrangeHRM\Tests\Util\KernelTestCase;

/**
 * @group Core
 * @group Helper
 */
class UserRoleManagerHelperTest extends KernelTestCase
{
    public function testGetDataGroupPermissionsForEmployeeWithoutAssignedEmployee(): void
    {
        $resourcePermission = new ResourcePermission(true, false, false, false);
        $userRole = new UserRole();
        $userRole->setId(1);
        $userRole->setName('Admin');
        $user = new User();
        $user->setId(1);
        $user->setUserRole($userRole);

        $userRoleManager = $this->getMockBuilder(BasicUserRoleManager::class)
            ->onlyMethods(['getDataGroupPermissions', 'getUser'])
            ->getMock();
        $userRoleManager->expects($this->once())
            ->method('getDataGroupPermissions')
            ->with('personal_information', [], [], false, [])
            ->willReturn($resourcePermission);
        $userRoleManager->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $this->createKernelWithMockServices([Services::USER_ROLE_MANAGER => $userRoleManager]);
        $userRoleManagerHelper = new UserRoleManagerHelper();
        $permission = $userRoleManagerHelper->getDataGroupPermissionsForEmployee('personal_information');
        $this->assertTrue($permission->canRead());
        $this->assertFalse($permission->canCreate());
        $this->assertFalse($permission->canUpdate());
        $this->assertFalse($permission->canDelete());
    }

    public function testGetDataGroupPermissionsForEmployeeWithAssignedEmployee(): void
    {
        $resourcePermission = new ResourcePermission(true, false, false, false);
        $employee = new Employee();
        $employee->setEmpNumber(2);
        $userRole = new UserRole();
        $userRole->setId(1);
        $userRole->setName('Admin');
        $user = new User();
        $user->setId(1);
        $user->setUserRole($userRole);
        $user->setEmployee($employee);

        $userRoleManager = $this->getMockBuilder(BasicUserRoleManager::class)
            ->onlyMethods(['getDataGroupPermissions', 'getUser'])
            ->getMock();
        $userRoleManager->expects($this->once())
            ->method('getDataGroupPermissions')
            ->with('personal_information', [], [], false, [])
            ->willReturn($resourcePermission);
        $userRoleManager->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $this->createKernelWithMockServices([Services::USER_ROLE_MANAGER => $userRoleManager]);
        $userRoleManagerHelper = new UserRoleManagerHelper();
        $permission = $userRoleManagerHelper->getDataGroupPermissionsForEmployee('personal_information');
        $this->assertTrue($permission->canRead());
        $this->assertFalse($permission->canCreate());
        $this->assertFalse($permission->canUpdate());
        $this->assertFalse($permission->canDelete());
    }

    public function testGetDataGroupPermissionsForEmployeeForSelfPermission(): void
    {
        $resourcePermission = new ResourcePermission(true, false, false, false);
        $employee = new Employee();
        $employee->setEmpNumber(2);
        $userRole = new UserRole();
        $userRole->setId(1);
        $userRole->setName('Admin');
        $user = new User();
        $user->setId(1);
        $user->setUserRole($userRole);
        $user->setEmployee($employee);

        $userRoleManager = $this->getMockBuilder(BasicUserRoleManager::class)
            ->onlyMethods(['getDataGroupPermissions', 'getUser'])
            ->getMock();
        $userRoleManager->expects($this->once())
            ->method('getDataGroupPermissions')
            ->with('personal_information', [], [], true, [Employee::class => 2])
            ->willReturn($resourcePermission);
        $userRoleManager->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $this->createKernelWithMockServices([Services::USER_ROLE_MANAGER => $userRoleManager]);
        $userRoleManagerHelper = new UserRoleManagerHelper();
        $permission = $userRoleManagerHelper->getDataGroupPermissionsForEmployee('personal_information', 2);
        $this->assertTrue($permission->canRead());
        $this->assertFalse($permission->canCreate());
        $this->assertFalse($permission->canUpdate());
        $this->assertFalse($permission->canDelete());
    }

    public function testGetDataGroupPermissionsForEmployeeForNotSelfPermission(): void
    {
        $resourcePermission = new ResourcePermission(true, false, false, false);
        $employee = new Employee();
        $employee->setEmpNumber(2);
        $userRole = new UserRole();
        $userRole->setId(1);
        $userRole->setName('Admin');
        $user = new User();
        $user->setId(1);
        $user->setUserRole($userRole);
        $user->setEmployee($employee);

        $userRoleManager = $this->getMockBuilder(BasicUserRoleManager::class)
            ->onlyMethods(['getDataGroupPermissions', 'getUser'])
            ->getMock();
        $userRoleManager->expects($this->once())
            ->method('getDataGroupPermissions')
            ->with('personal_information', [], [], false, [Employee::class => 1])
            ->willReturn($resourcePermission);
        $userRoleManager->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $this->createKernelWithMockServices([Services::USER_ROLE_MANAGER => $userRoleManager]);
        $userRoleManagerHelper = new UserRoleManagerHelper();
        $permission = $userRoleManagerHelper->getDataGroupPermissionsForEmployee('personal_information', 1);
        $this->assertTrue($permission->canRead());
        $this->assertFalse($permission->canCreate());
        $this->assertFalse($permission->canUpdate());
        $this->assertFalse($permission->canDelete());
    }
}
