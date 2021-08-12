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

namespace OrangeHRM\Tests\Leave\Api;

use DateTime;
use Generator;
use OrangeHRM\Authentication\Auth\User;
use OrangeHRM\Config\Config;
use OrangeHRM\Core\Api\CommonParams;
use OrangeHRM\Core\Api\V2\RequestParams;
use OrangeHRM\Core\Authorization\Manager\BasicUserRoleManager;
use OrangeHRM\Core\Service\DateTimeHelperService;
use OrangeHRM\Entity\LeaveEntitlement;
use OrangeHRM\Entity\LeaveType;
use OrangeHRM\Framework\Services;
use OrangeHRM\Leave\Api\LeaveEntitlementAPI;
use OrangeHRM\Leave\Dao\LeaveTypeDao;
use OrangeHRM\Leave\Service\LeaveEntitlementService;
use OrangeHRM\Leave\Service\LeaveTypeService;
use OrangeHRM\Tests\Util\EndpointTestCase;
use OrangeHRM\Tests\Util\MockObject;
use OrangeHRM\Tests\Util\TestDataService;

/**
 * @group Leave
 * @group APIv2
 */
class LeaveEntitlementAPITest extends EndpointTestCase
{
    protected function loadFixtures(): void
    {
        $fixture = Config::get(Config::PLUGINS_DIR) . '/orangehrmLeavePlugin/test/fixtures/LeaveEntitlementAPI.yml';
        TestDataService::populate($fixture);
    }

    public function testCreate(): void
    {
        $this->loadFixtures();
        $service = $this->getMockBuilder(LeaveEntitlementService::class)
            ->onlyMethods(['addEntitlementForEmployee'])
            ->getMock();
        $service->expects($this->once())
            ->method('addEntitlementForEmployee')
            ->willReturnCallback(function ($empNumber, $leaveTypeId, $fromDate, $toDate, $entitlement) {
                $leaveEntitlement = new LeaveEntitlement();
                $leaveEntitlement->setId(1);
                $leaveEntitlement->getDecorator()->setEmployeeByEmpNumber($empNumber);
                $leaveEntitlement->getDecorator()->setLeaveTypeById($leaveTypeId);
                $leaveEntitlement->getDecorator()->setEntitlementTypeById(3);
                $leaveEntitlement->setFromDate($fromDate);
                $leaveEntitlement->setToDate($toDate);
                $leaveEntitlement->setNoOfDays($entitlement);
                $leaveEntitlement->setCreditedDate(new DateTime('2021-08-11'));
                return $leaveEntitlement;
            });

        /** @var MockObject&LeaveEntitlementAPI $api */
        $api = $this->getApiEndpointMockBuilder(
            LeaveEntitlementAPI::class,
            [
                RequestParams::PARAM_TYPE_BODY => [
                    CommonParams::PARAMETER_EMP_NUMBER => 100,
                    LeaveEntitlementAPI::PARAMETER_LEAVE_TYPE_ID => 50,
                    LeaveEntitlementAPI::PARAMETER_FROM_DATE => '2021-01-01',
                    LeaveEntitlementAPI::PARAMETER_TO_DATE => '2021-12-31',
                    LeaveEntitlementAPI::PARAMETER_ENTITLEMENT => 5.00,
                ]
            ]
        )
            ->onlyMethods(['getLeaveEntitlementService'])
            ->getMock();
        $api->expects($this->once())
            ->method('getLeaveEntitlementService')
            ->willReturn($service);

        $this->createKernelWithMockServices([Services::DATETIME_HELPER_SERVICE => new DateTimeHelperService()]);
        $result = $api->create();
        $this->assertEquals(
            [
                'id' => 1,
                'empNumber' => 100,
                'entitlement' => 5.0,
                'daysUsed' => 0.0,
                'leaveType' => [
                    'id' => 50,
                    'name' => 'Test',
                    'deleted' => false,
                ],
                'fromDate' => '2021-01-01',
                'toDate' => '2021-12-31',
                'creditedDate' => '2021-08-11',
                'entitlementType' => [
                    'id' => 3,
                    'name' => 'Added',
                ],
                'deleted' => false,
            ],
            $result->normalize()
        );
        $this->assertNull($result->getMeta());
    }

    public function testGetValidationRuleForGetOne(): void
    {
        $userRoleManager = $this->getMockBuilder(BasicUserRoleManager::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getAccessibleEntityIds'])
            ->getMock();
        $userRoleManager->expects($this->once())
            ->method('getAccessibleEntityIds')
            ->willReturn([1, 2]);

        $authUser = $this->getMockBuilder(User::class)
            ->onlyMethods(['getEmpNumber'])
            ->disableOriginalConstructor()
            ->getMock();
        $authUser->expects($this->once())
            ->method('getEmpNumber')
            ->willReturn(1);

        $leaveTypeDao = $this->getMockBuilder(LeaveTypeDao::class)
            ->onlyMethods(['getLeaveTypeById'])
            ->getMock();
        $leaveTypeDao->expects($this->once())
            ->method('getLeaveTypeById')
            ->with(50)
            ->willReturn(new LeaveType());

        $leaveTypeService = $this->getMockBuilder(LeaveTypeService::class)
            ->onlyMethods(['getLeaveTypeDao'])
            ->getMock();
        $leaveTypeService->expects($this->once())
            ->method('getLeaveTypeDao')
            ->willReturn($leaveTypeDao);

        $this->createKernelWithMockServices(
            [
                Services::USER_ROLE_MANAGER => $userRoleManager,
                Services::AUTH_USER => $authUser,
                Services::LEAVE_TYPE_SERVICE => $leaveTypeService,
            ]
        );
        $api = new LeaveEntitlementAPI($this->getRequest());
        $rules = $api->getValidationRuleForCreate();
        $this->assertTrue(
            $this->validate(
                [
                    CommonParams::PARAMETER_EMP_NUMBER => 2,
                    LeaveEntitlementAPI::PARAMETER_LEAVE_TYPE_ID => 50,
                    LeaveEntitlementAPI::PARAMETER_FROM_DATE => '2021-01-01',
                    LeaveEntitlementAPI::PARAMETER_TO_DATE => '2021-12-31',
                    LeaveEntitlementAPI::PARAMETER_ENTITLEMENT => 5.00
                ],
                $rules
            )
        );
    }

    /**
     * @dataProvider getValidationRuleForGetOneExpectInvalidParamExceptionDataProvider
     * @param array $services
     * @param array $params
     */
    public function testGetValidationRuleForGetOneExpectInvalidParamException(array $services, array $params): void
    {
        $this->createKernelWithMockServices($services);
        $api = new LeaveEntitlementAPI($this->getRequest());
        $rules = $api->getValidationRuleForCreate();

        $this->expectInvalidParamException();
        $this->validate($params, $rules);
    }

    /**
     * @return Generator
     */
    public function getValidationRuleForGetOneExpectInvalidParamExceptionDataProvider(): Generator
    {
        $userRoleManager = $this->getMockBuilder(BasicUserRoleManager::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getAccessibleEntityIds'])
            ->getMock();
        $userRoleManager->method('getAccessibleEntityIds')
            ->willReturn([1, 2]);

        $authUser = $this->getMockBuilder(User::class)
            ->onlyMethods(['getEmpNumber'])
            ->disableOriginalConstructor()
            ->getMock();
        $authUser->method('getEmpNumber')
            ->willReturn(1);

        $leaveTypeDao = $this->getMockBuilder(LeaveTypeDao::class)
            ->onlyMethods(['getLeaveTypeById'])
            ->getMock();
        $leaveTypeDao->method('getLeaveTypeById')
            ->with(50)
            ->willReturn(new LeaveType());

        $leaveTypeService = $this->getMockBuilder(LeaveTypeService::class)
            ->onlyMethods(['getLeaveTypeDao'])
            ->getMock();
        $leaveTypeService->method('getLeaveTypeDao')
            ->willReturn($leaveTypeDao);

        yield [
            [
                Services::USER_ROLE_MANAGER => $userRoleManager,
                Services::AUTH_USER => $authUser,
                Services::LEAVE_TYPE_SERVICE => $leaveTypeService,
            ],
            [
                CommonParams::PARAMETER_EMP_NUMBER => 3,
                LeaveEntitlementAPI::PARAMETER_LEAVE_TYPE_ID => 50,
                LeaveEntitlementAPI::PARAMETER_FROM_DATE => '2021-01-01',
                LeaveEntitlementAPI::PARAMETER_TO_DATE => '2021-12-31',
                LeaveEntitlementAPI::PARAMETER_ENTITLEMENT => 5.00
            ]
        ];
        yield [
            [
                Services::USER_ROLE_MANAGER => $userRoleManager,
                Services::AUTH_USER => $authUser,
                Services::LEAVE_TYPE_SERVICE => $leaveTypeService,
            ],
            [
                CommonParams::PARAMETER_EMP_NUMBER => 2,
                LeaveEntitlementAPI::PARAMETER_LEAVE_TYPE_ID => 51,
                LeaveEntitlementAPI::PARAMETER_FROM_DATE => '2021-01-01',
                LeaveEntitlementAPI::PARAMETER_TO_DATE => '2021-12-31',
                LeaveEntitlementAPI::PARAMETER_ENTITLEMENT => 5.00
            ]
        ];
        yield [
            [
                Services::USER_ROLE_MANAGER => $userRoleManager,
                Services::AUTH_USER => $authUser,
                Services::LEAVE_TYPE_SERVICE => $leaveTypeService,
            ],
            [
                CommonParams::PARAMETER_EMP_NUMBER => 2,
                LeaveEntitlementAPI::PARAMETER_LEAVE_TYPE_ID => 50,
                LeaveEntitlementAPI::PARAMETER_FROM_DATE => '2021-01-32',
                LeaveEntitlementAPI::PARAMETER_TO_DATE => '2021-12-31',
                LeaveEntitlementAPI::PARAMETER_ENTITLEMENT => 5.00
            ]
        ];
        yield [
            [
                Services::USER_ROLE_MANAGER => $userRoleManager,
                Services::AUTH_USER => $authUser,
                Services::LEAVE_TYPE_SERVICE => $leaveTypeService,
            ],
            [
                CommonParams::PARAMETER_EMP_NUMBER => 2,
                LeaveEntitlementAPI::PARAMETER_LEAVE_TYPE_ID => 50,
                LeaveEntitlementAPI::PARAMETER_FROM_DATE => '2021-01-01',
                LeaveEntitlementAPI::PARAMETER_TO_DATE => '2021-11-31',
                LeaveEntitlementAPI::PARAMETER_ENTITLEMENT => 5.00
            ]
        ];
        yield [
            [
                Services::USER_ROLE_MANAGER => $userRoleManager,
                Services::AUTH_USER => $authUser,
                Services::LEAVE_TYPE_SERVICE => $leaveTypeService,
            ],
            [
                CommonParams::PARAMETER_EMP_NUMBER => 2,
                LeaveEntitlementAPI::PARAMETER_LEAVE_TYPE_ID => 'leaveId',
                LeaveEntitlementAPI::PARAMETER_FROM_DATE => '2021-01-01',
                LeaveEntitlementAPI::PARAMETER_TO_DATE => '2021-12-31',
                LeaveEntitlementAPI::PARAMETER_ENTITLEMENT => 5.00
            ]
        ];
        yield [
            [
                Services::USER_ROLE_MANAGER => $userRoleManager,
                Services::AUTH_USER => $authUser,
                Services::LEAVE_TYPE_SERVICE => $leaveTypeService,
            ],
            [
                CommonParams::PARAMETER_EMP_NUMBER => 2,
                LeaveEntitlementAPI::PARAMETER_LEAVE_TYPE_ID => 0,
                LeaveEntitlementAPI::PARAMETER_FROM_DATE => '2021-01-01',
                LeaveEntitlementAPI::PARAMETER_TO_DATE => '2021-12-31',
                LeaveEntitlementAPI::PARAMETER_ENTITLEMENT => 5.00
            ]
        ];
    }

    public function testDelete(): void
    {
        $api = new LeaveEntitlementAPI($this->getRequest());
        $this->expectNotImplementedException();
        $api->delete();
    }

    public function testGetValidationRuleForDelete(): void
    {
        $api = new LeaveEntitlementAPI($this->getRequest());
        $this->expectNotImplementedException();
        $api->getValidationRuleForDelete();
    }
}
