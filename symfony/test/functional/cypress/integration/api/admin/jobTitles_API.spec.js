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

import faker from 'faker';

const jobTitlesApi = `/api/v2/admin/job-titles`;

describe('Admin - Job Title API', function () {
  beforeEach(function () {
    cy.task('db:reset');
    cy.apiLogin(user.admin.userName, user.admin.password);
  });

  const dummyText = faker.lorem.paragraph(20);
  const jobTitleModel = {
    title: faker.name.title(),
    description: dummyText.substring(0, 150),
    note: dummyText.substring(0, 150),
  };

  describe('GET /job-titles', function () {
    it('gets a list of job titles', function () {
      cy.request('GET', jobTitlesApi).then((response) => {
        expect(response.status).to.eq(200);
        expect(response.body.data.length).to.eq(0);
      });
    });
  });

  describe('POST /job-titles', function () {
    it('creates a new job title', function () {
      cy.request('POST', jobTitlesApi, {
        title: jobTitleModel.title,
        description: jobTitleModel.description,
        specification: null,
        note: jobTitleModel.note,
      }).then((response) => {
        expect(response.status).to.eq(200);
      });
    });
  });
});
