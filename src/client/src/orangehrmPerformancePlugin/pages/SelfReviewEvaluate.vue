<!--
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
 -->

<template>
  <div class="orangehrm-background-container">
    <div class="orangehrm-card-container">
      <oxd-text tag="h5" class="orangehrm-performance-review-title">
        {{ $t('performance.performance_review') }}
      </oxd-text>
    </div>
    <br />
    <review-summary
      :emp-number="empNumber"
      :employee-name="employeeName"
      :job-title="jobTitle"
      :status="status"
      :review-period-start="reviewPeriodStart"
      :review-period-end="reviewPeriodEnd"
      :due-date="dueDate"
    />
  </div>
</template>

<script>
import ReviewSummary from '../components/ReviewSummary';

export default {
  name: 'SelfReviewEvaluate',
  components: {
    'review-summary': ReviewSummary,
  },
  props: {
    reviewId: {
      type: Number,
      required: true,
    },
    empNumber: {
      type: Number,
      required: true,
    },
    employeeName: {
      type: String,
      required: true,
    },
    jobTitle: {
      type: String,
      required: true,
    },
    status: {
      type: Number,
      required: true,
    },
    reviewPeriodStart: {
      type: String,
      required: true,
    },
    reviewPeriodEnd: {
      type: String,
      required: true,
    },
    dueDate: {
      type: String,
      required: true,
    },
  },
};
</script>

<style src="./review-evaluate.scss" lang="scss" scoped></style>
