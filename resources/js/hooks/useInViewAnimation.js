import { useEffect, useRef, useState } from 'react';

const DEFAULT_OPTIONS = {
    threshold: 0.12,
    rootMargin: '0px 0px -40px 0px', // dispara un poco antes de entrar
};

/**
 * Hook optimizado para animaciones de entrada al hacer scroll.
 * Usa IntersectionObserver con rootMargin para disparar antes y una sola vez.
 * Devuelve ref y visible (true cuando el elemento entra al viewport).
 */
export default function useInViewAnimation() {
    const ref = useRef(null);
    const [visible, setVisible] = useState(false);

    useEffect(() => {
        const el = ref.current;
        if (!el || visible) return;

        const observer = new IntersectionObserver(
            (entries) => {
                const [entry] = entries;
                if (!entry.isIntersecting) return;
                setVisible(true);
                observer.disconnect();
            },
            { threshold: DEFAULT_OPTIONS.threshold, rootMargin: DEFAULT_OPTIONS.rootMargin }
        );

        observer.observe(el);
        return () => observer.disconnect();
    }, [visible]);

    return { ref, visible };
}

