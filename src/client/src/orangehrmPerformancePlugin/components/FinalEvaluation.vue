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
  <div>
    <oxd-text class="orangehrm-performance-review-title">
      {{ $t('performance.review_finalization') }}
    </oxd-text>
    <div class="orangehrm-performance-review-final">
      <div class="orangehrm-performance-review-final-date">
        <oxd-text class="orangehrm-performance-review-bold">
          {{ $t('performance.date_of_completion') }}
        </oxd-text>
        <date-input
          :model-value="completedDate"
          :readonly="readOnly"
          :rules="rules.completedDate"
          @update:modelValue="$emit('update:completedDate', $event)"
        />
      </div>
      <div class="orangehrm-performance-review-final-rating">
        <oxd-text class="orangehrm-performance-review-bold">
          {{ $t('performance.final_rating') }}
        </oxd-text>
        <oxd-input-field
          :model-value="finalRating"
          :readonly="readOnly"
          :rules="rules.finalRating"
          @update:modelValue="$emit('update:finalRating', $event)"
        />
      </div>
      <div class="orangehrm-performance-review-final-comment">
        <oxd-text class="orangehrm-performance-review-bold">
          {{ $t('performance.final_comments') }}
        </oxd-text>
        <oxd-input-field
          :model-value="finalComment"
          :readonly="readOnly"
          :rules="rules.finalComment"
          @update:modelValue="$emit('update:finalComment', $event)"
        />
      </div>
    </div>
  </div>
</template>

<script>
import {computed} from 'vue';
import {
  digitsOnlyWithDecimalPoint,
  required,
  validDateFormat,
} from '@/core/util/validation/rules';

export default {
  name: 'FinalEvaluation',
  props: {
    completedDate: {
      type: String,
      required: true,
    },
    finalRating: {
      type: String,
      required: true,
    },
    finalComment: {
      type: String,
      required: true,
    },
    status: {
      type: Number,
      required: true,
    },
  },
  emits: ['update:finalRating', 'update:finalComment', 'update:completedDate'],
  setup(props) {
    const readOnly = computed(() => props.status === 4);

    return {
      readOnly,
    };
  },
  data() {
    return {
      rules: {
        completedDate: [required, validDateFormat()],
        finalRating: [required, digitsOnlyWithDecimalPoint],
        // TODO add min max rules
        finalComment: [required],
      },
    };
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

  &-final {
    display: flex;
    flex-direction: row;
    margin-top: 1.2rem;
    margin-bottom: 1.2rem;

    &-date {
      width: 30%;
      margin-right: 2.4rem;
    }

    &-rating {
      width: 10%;
      margin-right: 2.4rem;
      //overflow: hidden;
      //white-space: nowrap;
      //text-overflow: ellipsis;
    }

    &-comment {
      width: 65%;
    }
  }
}
</style>
