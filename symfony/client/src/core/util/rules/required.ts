export default function required(value: string): boolean | string {
  return (!!value && value.trim() !== '') || 'Required';
}
