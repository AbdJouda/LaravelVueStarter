import routes from '@/router/routes'

const navigation = () => {
    return routes.reduce((prev, curr) => {
        const {meta, path, name, children} = curr
        if (meta?.icon) {
            prev.push({
                name,
                href: path,
                icon: meta.icon,
                title: meta.title,
                children: children?.filter(child => child.meta?.icon),
                requiresAuth: meta.requiresAuth,
                permissions: meta.permissions
            })
        }
        return prev
    }, [])
}

export default navigation
