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

namespace OrangeHRM\Leave\Event;

final class LeaveEvent
{
    /**
     * @see \OrangeHRM\Leave\Event\LeaveApply
     */
    public const APPLY = 'leave.apply';

    /**
     * @see \OrangeHRM\Leave\Event\LeaveAssign
     */
    public const ASSIGN = 'leave.assign';

    /**
     * @see \OrangeHRM\Leave\Event\LeaveApprove
     */
    public const APPROVE = 'leave.approve';

    /**
     * @see \OrangeHRM\Leave\Event\LeaveCancel
     */
    public const CANCEL = 'leave.cancel';

    /**
     * @see \OrangeHRM\Leave\Event\LeaveReject
     */
    public const REJECT = 'leave.reject';
}
