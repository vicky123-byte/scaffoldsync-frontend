import React, { PropsWithChildren } from 'react';
import { Pressable, StyleSheet, Text, TextInput, TextInputProps, View, ViewStyle } from 'react-native';
import { theme } from '../theme';

export function PageHeading({ title, subtitle }: { title: string; subtitle?: string }) {
  return <View style={styles.heading}><Text style={styles.title}>{title}</Text>{subtitle ? <Text style={styles.subtitle}>{subtitle}</Text> : null}</View>;
}

export function Card({ children, style }: PropsWithChildren<{ style?: ViewStyle }>) {
  return <View style={[styles.card, style]}>{children}</View>;
}

export function AppButton({ title, onPress, variant = 'primary', disabled = false }: {
  title: string; onPress: () => void; variant?: 'primary' | 'secondary' | 'outline' | 'danger'; disabled?: boolean;
}) {
  const variantStyle = { primary: styles.button_primary, secondary: styles.button_secondary, outline: styles.button_outline, danger: styles.button_danger }[variant];
  return <Pressable accessibilityRole="button" disabled={disabled} onPress={onPress} style={({ pressed }) => [styles.button, variantStyle, disabled && styles.disabled, pressed && !disabled && styles.pressed]}><Text style={[styles.buttonText, variant !== 'primary' && variant !== 'danger' && styles.buttonTextSecondary]}>{title}</Text></Pressable>;
}

export function AppField({ label, ...props }: TextInputProps & { label: string }) {
  return <View style={styles.fieldWrap}><Text style={styles.label}>{label}</Text><TextInput placeholderTextColor={theme.colors.muted} {...props} style={[styles.input, props.multiline && styles.multiline, props.style]} /></View>;
}

export function AppBadge({ text, tone = 'blue' }: { text: string; tone?: 'blue' | 'green' | 'orange' | 'red' | 'purple' | 'gray' }) {
  const badgeStyle = { blue: styles.badge_blue, green: styles.badge_green, orange: styles.badge_orange, red: styles.badge_red, purple: styles.badge_purple, gray: styles.badge_gray }[tone];
  const textStyle = { blue: styles.badgeText_blue, green: styles.badgeText_green, orange: styles.badgeText_orange, red: styles.badgeText_red, purple: styles.badgeText_purple, gray: styles.badgeText_gray }[tone];
  return <View style={[styles.badge, badgeStyle]}><Text style={[styles.badgeText, textStyle]}>{text}</Text></View>;
}

export function Row({ children, style }: PropsWithChildren<{ style?: ViewStyle }>) {
  return <View style={[styles.row, style]}>{children}</View>;
}

export function SectionTitle({ children }: PropsWithChildren) {
  return <Text style={styles.sectionTitle}>{children}</Text>;
}

export function Divider() { return <View style={styles.divider} />; }

const styles = StyleSheet.create({
  heading: { marginBottom: 16 }, title: { color: theme.colors.navy, fontSize: 23, fontWeight: '800' }, subtitle: { color: theme.colors.muted, fontSize: 12, lineHeight: 18, marginTop: 5 },
  card: { backgroundColor: theme.colors.surface, borderRadius: theme.radius.lg, padding: 16, borderWidth: 1, borderColor: theme.colors.border, marginBottom: 13 },
  button: { minHeight: 45, borderRadius: 10, paddingHorizontal: 15, paddingVertical: 12, alignItems: 'center', justifyContent: 'center', marginTop: 8 }, button_primary: { backgroundColor: theme.colors.primary }, button_secondary: { backgroundColor: '#EAF1F7' }, button_outline: { backgroundColor: '#FFFFFF', borderWidth: 1, borderColor: theme.colors.border }, button_danger: { backgroundColor: theme.colors.red }, buttonText: { color: '#FFFFFF', fontSize: 13, fontWeight: '800' }, buttonTextSecondary: { color: theme.colors.primaryDark }, disabled: { opacity: 0.45 }, pressed: { opacity: 0.82 },
  fieldWrap: { marginBottom: 11 }, label: { color: theme.colors.text, fontSize: 11, fontWeight: '700', marginBottom: 6 }, input: { minHeight: 44, borderWidth: 1, borderColor: theme.colors.border, borderRadius: 9, paddingHorizontal: 12, paddingVertical: 10, backgroundColor: '#FFFFFF', color: theme.colors.navy, fontSize: 13 }, multiline: { minHeight: 82, textAlignVertical: 'top' },
  badge: { alignSelf: 'flex-start', borderRadius: 20, paddingHorizontal: 9, paddingVertical: 5 }, badge_blue: { backgroundColor: '#E5F0FB' }, badge_green: { backgroundColor: '#E4F6ED' }, badge_orange: { backgroundColor: '#FFF0DD' }, badge_red: { backgroundColor: '#FDE9E8' }, badge_purple: { backgroundColor: '#EEEAFD' }, badge_gray: { backgroundColor: '#EDF1F5' }, badgeText: { fontSize: 10, fontWeight: '800' }, badgeText_blue: { color: '#2672BC' }, badgeText_green: { color: '#16965E' }, badgeText_orange: { color: '#E58717' }, badgeText_red: { color: '#D94848' }, badgeText_purple: { color: '#755BB1' }, badgeText_gray: { color: '#64748B' },
  row: { flexDirection: 'row', alignItems: 'center', gap: 10 }, sectionTitle: { fontSize: 15, fontWeight: '800', color: theme.colors.navy, marginTop: 9, marginBottom: 11 }, divider: { height: 1, backgroundColor: theme.colors.border, marginVertical: 13 },
});
