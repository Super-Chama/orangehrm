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

import Employee from './pages/employee/Employee.vue';
import SaveEmployee from './pages/employee/SaveEmployee.vue';
import EmployeeReport from './pages/reports/EmployeeReport.vue';
import UpdatePassword from './pages/updatePassword/UpdatePassword.vue';
// import EmployeePersonalDetails from './pages/employee/EmployeePersonalDetails.vue';
// import EmployeeContactDetails from './pages/employee/EmployeeContactDetails.vue';
// import EmployeeEmergencyContacts from './pages/employee/EmployeeEmergencyContacts.vue';
// import EmployeeDependents from './pages/employee/EmployeeDependents.vue';
// import EmployeeProfilePicture from './pages/employee/EmployeeProfilePicture.vue';
// import EmployeeSalary from './pages/employee/EmployeeSalary.vue';
// import EmployeeJob from './pages/employee/EmployeeJob.vue';
// import EmployeeQualifications from './pages/employee/EmployeeQualifications.vue';
// import EmployeeImmigration from './pages/employee/EmployeeImmigration.vue';
// import EmployeeReportTo from './pages/employee/EmployeeReportTo.vue';
// import EmployeeMembership from './pages/employee/EmployeeMembership.vue';
// import TerminationReason from './pages/terminationReason/TerminationReason.vue';
// import EditTerminationReason from './pages/terminationReason/EditTerminationReason.vue';
// import SaveTerminationReason from './pages/terminationReason/SaveTerminationReason.vue';
// import ReportingMethod from './pages/reportingMethod/ReportingMethod.vue';
// import EditReportingMethod from './pages/reportingMethod/EditReportingMethod.vue';
// import SaveReportingMethod from './pages/reportingMethod/SaveReportingMethod.vue';
// import CustomField from './pages/customField/CustomField.vue';
// import EditCustomField from './pages/customField/EditCustomField.vue';
// import SaveCustomField from './pages/customField/SaveCustomField.vue';
// import OptionalField from './pages/optionalField/OptionalField.vue';
// import EmployeeTaxExemption from './pages/employee/EmployeeTaxExemption.vue';
// import EmployeeDataImport from './pages/dataImport/EmployeeDataImport.vue';
// import SaveEmployeeReport from './pages/reports/SaveEmployeeReport.vue';
// import ViewEmployeeReport from './pages/reports/ViewEmployeeReport.vue';
// import EditEmployeeReport from './pages/reports/EditEmployeeReport.vue';

const pimRoutes = [
  {
    path: '',
    name: 'viewEmployeeList',
    component: Employee,
  },
  {
    path: 'add-employee',
    name: 'AddEmployee',
    component: SaveEmployee,
  },
  {
    path: 'update-password',
    name: 'updatePassword',
    component: UpdatePassword,
    props: {userName: 'Admin'},
  },
  {
    path: 'employee-report',
    name: 'ViewEmployeeReport',
    component: EmployeeReport,
  },
];

export default pimRoutes;
