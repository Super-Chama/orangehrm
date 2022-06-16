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

namespace OrangeHRM\Performance\Controller;

use OrangeHRM\Core\Controller\Common\NoRecordsFoundController;
use OrangeHRM\Core\Controller\Exception\RequestForwardableException;
use OrangeHRM\Core\Traits\UserRoleManagerTrait;
use OrangeHRM\Core\Vue\Component;
use OrangeHRM\Framework\Http\Request;
use OrangeHRM\Performance\Traits\Service\PerformanceReviewServiceTrait;

class SelfReviewEvaluateController extends ReviewEvaluateController
{
    use PerformanceReviewServiceTrait;
    use UserRoleManagerTrait;

    /**
     * @inheritDoc
     */
    public function preRender(Request $request): void
    {
        $id = $request->attributes->getInt('id');
        $component = new Component('self-review-evaluate');

        $review = $this->getPerformanceReviewService()->getPerformanceReviewDao()->getPerformanceReviewById($id);
        if (!is_null($review)) {
            $this->setReviewProps($component, $review);
        }
        $this->setComponent($component);
    }

    /**
     * @inheritDoc
     */
    public function isCapable(Request $request): bool
    {
        $id = $request->attributes->getInt('id');
        $review = $this->getPerformanceReviewService()->getPerformanceReviewDao()->getPerformanceReviewById($id);
        if (is_null($review)) {
            throw new RequestForwardableException(NoRecordsFoundController::class . '::handle');
        }
        return $this->getUserRoleManagerHelper()->isSelfByEmpNumber($review->getEmployee()->getEmpNumber());
    }
}
