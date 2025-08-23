import { usePage } from '@inertiajs/vue3'

export function useAccess(menuKey: string) {
  const page: any = usePage()
  const sidebar = page.props.sidebar || []

  const menu = sidebar.find((m: any) => m.slug === menuKey)
  if (!menu) return { can: () => false }

  function can(action: string) {
    return menu.actions && menu.actions.includes(action)
  }

  return { can }
}