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
    <div class="orangehrm-card-container">
      <oxd-text tag="h5" class="orangehrm-performance-review-title">
        {{ $t('performance.review_summary') }}
      </oxd-text>
      <div class="orangehrm-performance-review-owner">
        <img alt="profile picture" class="employee-image" :src="imgSrc" />
        <div class="orangehrm-performance-review-owner-employee-section">
          <div class="orangehrm-performance-review-owner-employee">
            <oxd-text
              tag="h5"
              class="orangehrm-performance-review-owner-employee-name"
            >
              {{ employeeName }}
            </oxd-text>
            <oxd-text
              tag="h6"
              class="orangehrm-performance-review-owner-employee-job"
            >
              {{ jobTitle }}
            </oxd-text>
          </div>
        </div>
      </div>
      <div class="orangehrm-performance-review-details">
        <div class="orangehrm-performance-review-details-column">
          <oxd-text type="subtitle-2">
            {{ $t('performance.review_status') }}
          </oxd-text>
          <oxd-text class="orangehrm-performance-review-bold">
            {{ reviewStatus }}
          </oxd-text>
        </div>
        <div class="orangehrm-performance-review-details-column">
          <oxd-text type="subtitle-2">
            {{ $t('performance.review_period') }}
          </oxd-text>
          <oxd-text class="orangehrm-performance-review-bold">
            {{ reviewPeriod }}
          </oxd-text>
        </div>
        <div class="orangehrm-performance-review-details-column">
          <oxd-text type="subtitle-2">
            {{ $t('performance.review_due_date') }}
          </oxd-text>
          <oxd-text class="orangehrm-performance-review-bold">
            {{ reviewDueDate }}
          </oxd-text>
        </div>
      </div>
    </div>
    <br />
    <div class="orangehrm-card-container">
      <oxd-form :loading="isLoading" @submitValid="onClickSave(true)">
        <oxd-divider />
        <final-evaluation
          v-model:completed-date="completedDate"
          v-model:final-rating="finalRating"
          v-model:final-comment="finalComment"
          :read-only="readOnly"
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
import {formatDate, parseDate} from '@ohrm/core/util/helper/datefns';
import useLocale from '@/core/util/composable/useLocale';
import useDateFormat from '@/core/util/composable/useDateFormat';
import usei18n from '@/core/util/composable/usei18n';
import Divider from '@ohrm/oxd/core/components/Divider/Divider.vue';
import FinalEvaluation from '@/orangehrmPerformancePlugin/components/FinalEvaluation';
import {reloadPage} from '@/core/util/helper/navigation';

export default {
  name: 'ReviewEvaluate',
  components: {
    'oxd-divider': Divider,
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
  setup(props) {
    const {$t} = usei18n();
    const {jsDateFormat} = useDateFormat();
    const {locale} = useLocale();

    const http = new APIService(window.appGlobal.baseUrl, '');

    const statusOpts = [
      {id: 1, label: $t('performance.inactive')},
      {id: 2, label: $t('performance.activated')},
      {id: 3, label: $t('performance.in_progress')},
      {id: 4, label: $t('performance.completed')},
    ];

    const reviewDateFormat = date =>
      formatDate(parseDate(date), jsDateFormat, {locale});

    const imgSrc = `${window.appGlobal.baseUrl}/pim/viewPhoto/empNumber/${props.empNumber}`;
    const reviewStatus = statusOpts.find(el => el.id === props.status).label;
    const reviewPeriod = `${reviewDateFormat(props.reviewPeriodStart)} - ${
      props.reviewPeriodEnd
    }`;
    const reviewDueDate = reviewDateFormat(props.dueDate);

    const readOnly = props.status === 4;

    return {
      http,
      imgSrc,
      reviewStatus,
      reviewPeriod,
      reviewDueDate,
      readOnly,
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

<style lang="scss" scoped>
.orangehrm-performance-review {
  &-title {
    font-size: 14px;
    font-weight: 800;
  }

  &-bold {
    font-weight: 700;
  }

  &-owner {
    display: flex;
    flex-direction: row;
    align-items: center;
    margin-top: 1.2rem;
    margin-bottom: 1.2rem;

    & img {
      width: 100px;
      height: 100px;
      border-radius: 100%;
      display: flex;
      overflow: hidden;
      justify-content: center;
      box-sizing: border-box;
    }

    &-employee-section {
      display: flex;
    }

    &-employee {
      display: flex;
      flex-direction: column;
      padding-left: 1.2rem;
      padding-right: 0.6rem;
      padding-top: 1.2rem;

      &-name {
        font-weight: 700;
        font-size: 21px;
      }

      &-job {
        font-weight: 700;
        color: $oxd-interface-gray-color;
      }
    }
  }

  &-details {
    display: flex;
    flex-direction: row;

    &-column {
      width: 30%;
    }
  }
}
</style>
