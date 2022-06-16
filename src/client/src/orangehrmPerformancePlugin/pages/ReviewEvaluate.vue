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
    <br />
    <div class="orangehrm-card-container">
      <oxd-form :loading="isLoading" @submitValid="onClickSave(true)">
        <oxd-divider />
        <final-evaluation
          v-model:completed-date="completedDate"
          v-model:final-rating="finalRating"
          v-model:final-comment="finalComment"
          :status="status"
        />
        <oxd-divider />
        <oxd-form-actions>
          <oxd-button display-type="ghost" :label="$t('general.back')" />
          <oxd-button
            display-type="ghost"
            class="orangehrm-left-space"
            :label="$t('general.save')"
            @click="onClickSave(false)"
          />
          <oxd-button
            display-type="secondary"
            class="orangehrm-left-space"
            :label="$t('performance.complete')"
            type="submit"
          />
        </oxd-form-actions>
      </oxd-form>
    </div>
  </div>
</template>

<script>
import {APIService} from '@/core/util/services/api.service';
import {reloadPage} from '@/core/util/helper/navigation';
import Divider from '@ohrm/oxd/core/components/Divider/Divider.vue';
import ReviewSummary from '../components/ReviewSummary';
import FinalEvaluation from '../components/FinalEvaluation';

export default {
  name: 'ReviewEvaluate',
  components: {
    'oxd-divider': Divider,
    'review-summary': ReviewSummary,
    'final-evaluation': FinalEvaluation,
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
  setup() {
    const http = new APIService(window.appGlobal.baseUrl, '');

    return {
      http,
    };
  },
  data() {
    return {
      isLoading: false,
      required: false,
      completedDate: null,
      finalRating: null,
      finalComment: null,
    };
  },
  beforeMount() {
    this.isLoading = true;
    this.http
      .request({
        method: 'GET',
        url: `/api/v2/performance/reviews/${this.reviewId}/evaluation/final`,
      })
      .then(response => {
        const {data} = response.data;
        this.completedDate = data.completedDate;
        this.finalRating = data.finalRating;
        this.finalComment = data.finalComment;
      })
      .finally(() => {
        this.isLoading = false;
      });
  },
  methods: {
    onClickSave(complete = false) {
      this.isLoading = true;
      this.http
        .request({
          method: 'PUT',
          url: `/api/v2/performance/reviews/${this.reviewId}/evaluation/final`,
          data: {
            complete: complete,
            completedDate: this.completedDate,
            finalComment: this.finalComment,
            finalRating: this.finalRating,
          },
        })
        .then(() => {
          return this.$toast.saveSuccess();
        })
        .finally(() => {
          reloadPage();
        });
    },
  },
};
</script>

<style src="./review-evaluate.scss" lang="scss" scoped></style>
