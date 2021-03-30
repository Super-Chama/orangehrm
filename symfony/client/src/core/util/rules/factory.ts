import required from '@/core/util/rules/required';
import max from '@/core/util/rules/max';

const rules: any = {
  required,
  max,
};

export default function rulesFactory(rule: string) {
  return typeof rules[rule] === undefined ? null : rules[rule];
}
