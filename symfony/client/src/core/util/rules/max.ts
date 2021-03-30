export default function required(
  value: string,
  maxChars: number,
): boolean | string {
  return (
    (value && value.length <= maxChars) ||
    `Should be less than ${maxChars} characters`
  );
}
