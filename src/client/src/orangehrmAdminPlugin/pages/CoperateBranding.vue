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
      <oxd-text tag="h6" class="orangehrm-main-title">
        Coperate Branding
      </oxd-text>

      <oxd-divider />

      <oxd-form>
        <oxd-form-row>
          <oxd-input-group label="Primary Color">
            <input v-model="primaryColor" type="color" name="Primary Color" />
          </oxd-input-group>
        </oxd-form-row>

        <oxd-form-row>
          <oxd-input-group label="Secondary Color">
            <input
              v-model="secondaryColor"
              type="color"
              name="Secondary Color"
            />
          </oxd-input-group>
        </oxd-form-row>

        <oxd-form-row>
          <oxd-input-group label="Primary Gradient #1">
            <input
              v-model="primaryGradientStart"
              type="color"
              name="Primary Gradient #1"
            />
          </oxd-input-group>
        </oxd-form-row>

        <oxd-form-row>
          <oxd-input-group label="Primary Gradient #2">
            <input
              v-model="primaryGradientEnd"
              type="color"
              name="Primary Gradient #2"
            />
          </oxd-input-group>
        </oxd-form-row>

        <oxd-divider />

        <oxd-form-actions>
          <oxd-button
            type="button"
            display-type="ghost"
            :label="$t('general.cancel')"
          />
          <submit-button />
        </oxd-form-actions>
      </oxd-form>
    </div>
  </div>
</template>

<script>
import {ref, watch} from 'vue';
export default {
  setup() {
    const primaryColor = ref('#ff7b1d');
    const secondaryColor = ref('#76bc21');
    const primaryGradientStart = ref('#ff8226');
    const primaryGradientEnd = ref('#f35c17');

    watch(primaryColor, value => {
      document.documentElement.style.setProperty(
        '--oxd-primary-one-color',
        value,
      );
    });

    watch(secondaryColor, value => {
      document.documentElement.style.setProperty(
        '--oxd-secondary-four-color',
        value,
      );
    });

    watch(primaryGradientStart, value => {
      document.documentElement.style.setProperty(
        '--oxd-primary-gradient-start-color',
        value,
      );
    });

    watch(primaryGradientEnd, value => {
      document.documentElement.style.setProperty(
        '--oxd-primary-gradient-end-color',
        value,
      );
    });

    return {
      primaryColor,
      secondaryColor,
      primaryGradientStart,
      primaryGradientEnd,
    };
  },
};
</script>
