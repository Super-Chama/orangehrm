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

import {AxiosResponse} from 'axios';
import {promiseDebounce} from '@ohrm/oxd';
import {APIService} from '@/core/util/services/api.service';
import {translate as translatorFactory} from '@/core/plugins/i18n/translate';

type useServerValidationOptions = {
  debounce?: boolean;
  debounceOffset?: number;
};

interface UniqueValidationResponse {
  data: {
    valid: boolean;
  };
  meta: [];
}

export default function useServerValidation(
  http: APIService,
  options: useServerValidationOptions = {debounce: true, debounceOffset: 500},
) {
  const translate = translatorFactory();

  // TODO Add comment to explain matchByField & matchByValue
  const createUniqueValidator = (
    entityName: string,
    attributeName: string,
    entityId?: number,
    matchByField?: string,
    matchByValue?: string,
  ) => {
    const validationRequest = (value: string) => {
      return new Promise((resolve, reject) => {
        if (value.trim()) {
          http
            .request({
              method: 'GET',
              url: 'api/v2/admin/validation/unique',
              params: {
                value,
                entityId,
                entityName,
                attributeName,
                matchByField,
                matchByValue,
              },
            })
            .then((response: AxiosResponse<UniqueValidationResponse>) => {
              const {data} = response.data;
              if (data.valid === true) {
                resolve(true);
              } else {
                resolve(translate('general.already_exists'));
              }
            })
            .catch((error) => reject(error));
        } else {
          resolve(true);
        }
      });
    };

    return options.debounce
      ? promiseDebounce(validationRequest, options.debounceOffset)
      : validationRequest;
  };

  return {
    createUniqueValidator,
  };
}
