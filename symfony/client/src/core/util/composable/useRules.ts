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
import rulesFactory from '@/core/util/rules/factory';

interface Rules {
  [key: string]: Array<string>;
}

interface ResolvedRules {
  [key: string]: Array<Function>;
}

function parseRules(rulesArr: Array<string>) {
  return rulesArr.map(ruleStr => {
    const rules = ruleStr
      .toLowerCase()
      .split('|')
      .filter(i => i);
    return {
      rule: rules.splice(0, 1)[0],
      params: rules,
    };
  });
}

export default function useRules(_rules: Rules) {
  const rules: ResolvedRules = {};
  for (const [key, value] of Object.entries(_rules)) {
    const parsedRules = parseRules(value);
    Object.assign(rules, {[key]: []});
    parsedRules.forEach(i => {
      rules[key].push(rulesFactory(i.rule));
    });
  }

  return {
    rules,
  };
}
