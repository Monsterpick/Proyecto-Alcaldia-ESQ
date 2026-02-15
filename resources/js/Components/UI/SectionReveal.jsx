import useInViewAnimation from '@/hooks/useInViewAnimation';

/**
 * Envuelve una sección para animarla al entrar en viewport (opacity + translateY).
 * Usa solo CSS transition cuando visible, optimizado para GPU.
 */
export default function SectionReveal({ children, className = '', as: Tag = 'section', ...props }) {
    const { ref, visible } = useInViewAnimation();

    return (
        <Tag
            ref={ref}
            data-visible={visible}
            className={`transition-[opacity,transform] duration-700 ease-out ${
                visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'
            } ${className}`}
            style={{ willChange: visible ? 'auto' : 'opacity, transform' }}
            {...props}
        >
            {children}
        </Tag>
    );
}
