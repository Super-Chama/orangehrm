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

import {reactive, toRefs} from 'vue';
import {APIService} from '@/core/util/services/api.service';
import {AxiosResponse} from 'axios';

interface ServerResponse {
  data?: any;
  meta?: any;
  error?: boolean;
  message?: string;
}

interface DTO {
  [key: string]: any;
}

interface State {
  isLoading: boolean;
  dto: DTO;
}

async function insertData(
  http: APIService,
  data: DTO,
): Promise<ServerResponse> {
  try {
    const response: AxiosResponse = await http.create(data);
    return {
      data: response.data.data,
      meta: response.data.meta,
      error: false,
    };
  } catch (error) {
    return {
      error: true,
      message: error.message,
    };
  }
}

/* Override to mutate fields before update */
function defaultSerializer(dto: DTO): DTO {
  return dto;
}

export default function useInsert(
  apiPath: string,
  dto: DTO,
  serializer = defaultSerializer,
) {
  const http: APIService = new APIService(
    process.env.VUE_APP_API_ENDPOINT,
    apiPath,
  );

  const state = reactive<State>({
    isLoading: false,
    dto: {
      ...dto,
    },
  });

  /* For Inserting Data */
  const execQuery = async (): Promise<ServerResponse> => {
    state.isLoading = true;
    const response: ServerResponse = await insertData(
      http,
      serializer(state.dto),
    );
    // Reset
    state.dto = {
      ...dto,
    };
    state.isLoading = false;
    return response;
  };

  return {
    ...toRefs(state),
    execQuery,
  };
}
