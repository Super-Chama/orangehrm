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

import axios, {AxiosError, AxiosInstance, AxiosResponse} from 'axios';
import {ComponentInternalInstance, getCurrentInstance} from 'vue';

export class APIService {
  private _http: AxiosInstance;
  private _baseUrl: string;
  private _apiSection: string;

  constructor(baseUrl: string, path: string) {
    this._baseUrl = baseUrl;
    this._apiSection = path;
    this._http = axios.create({
      baseURL: this._baseUrl,
      timeout: 3000,
    });
    this.setupResponseInterceptors(getCurrentInstance());
  }

  getAll(params?: object): Promise<AxiosResponse> {
    const headers = {
      'Content-Type': 'application/json',
      Accept: 'application/json',
      'Cache-Control':
        'no-store, no-cache, must-revalidate, post-check=0, pre-check=0',
    };
    return this._http.get(this._apiSection, {headers, params});
  }

  get(id: number, params?: object): Promise<AxiosResponse> {
    const headers = {
      'Content-Type': 'application/json',
    };
    return this._http.get(`${this._apiSection}/${id}`, {headers, params});
  }

  create(data: any): Promise<AxiosResponse> {
    const headers = {
      'Content-Type': 'application/json',
      Accept: 'application/json',
    };
    return this._http.post(this._apiSection, data, {headers});
  }

  update(id: number, data: any): Promise<AxiosResponse> {
    const headers = {
      'Content-Type': 'application/json',
    };
    return this._http.put(`${this._apiSection}/${id}`, data, {headers});
  }

  delete(id: number): Promise<AxiosResponse> {
    const headers = {
      'Content-Type': 'application/json',
    };
    return this._http.delete(`${this._apiSection}/${id}`, {headers});
  }

  deleteAll(data?: any): Promise<AxiosResponse> {
    const headers = {
      'Content-Type': 'application/json',
    };
    return this._http.delete(`${this._apiSection}`, {headers, data});
  }

  /**
   * ComponentInternalInstance is given to access $toast api.
   * will fail silently if $toast is not installed/NA
   */
  setupResponseInterceptors(vm: ComponentInternalInstance | null): void {
    this._http.interceptors.response.use(
      (response: AxiosResponse): AxiosResponse => {
        return response;
      },
      (error: AxiosError): Promise<AxiosError> => {
        const $toast = vm?.appContext.config.globalProperties.$toast;
        if ($toast) {
          $toast.error({
            title: 'Error',
            message: error.message ?? 'Unexpected Error!',
          });
        }
        return Promise.reject(error);
      },
    );
  }

  public get http() {
    return this._http;
  }

  public get baseUrl() {
    return this._baseUrl;
  }

  public set apiSection(path: string) {
    this._apiSection = path;
  }
}
