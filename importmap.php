<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'app' => [
        'path' => './assets/app.js',
        'entrypoint' => true,
    ],
    '@hotwired/stimulus' => [
        'version' => '3.2.2',
    ],
    '@symfony/stimulus-bundle' => [
        'path' => './vendor/symfony/stimulus-bundle/assets/dist/loader.js',
    ],
    '@hotwired/turbo' => [
        'version' => '7.3.0',
    ],
    'react' => [
        'version' => '18.3.1',
    ],
    'react-bootstrap' => [
        'version' => '2.10.7',
    ],
    '@restart/ui/Anchor' => [
        'version' => '1.9.2',
    ],
    '@restart/ui/ssr' => [
        'version' => '1.9.2',
    ],
    'classnames' => [
        'version' => '2.5.1',
    ],
    'uncontrollable' => [
        'version' => '7.2.1',
    ],
    'react/jsx-runtime' => [
        'version' => '19.0.0',
    ],
    'dom-helpers/css' => [
        'version' => '5.2.1',
    ],
    'react-transition-group/Transition' => [
        'version' => '4.4.5',
    ],
    '@restart/ui/utils' => [
        'version' => '1.9.2',
    ],
    'dom-helpers/transitionEnd' => [
        'version' => '5.2.1',
    ],
    '@restart/hooks/useMergedRefs' => [
        'version' => '0.4.16',
    ],
    'react-dom' => [
        'version' => '19.0.0',
    ],
    '@restart/hooks/useEventCallback' => [
        'version' => '0.4.16',
    ],
    'prop-types' => [
        'version' => '15.8.1',
    ],
    '@restart/ui/Button' => [
        'version' => '1.9.2',
    ],
    '@restart/hooks/useUpdateEffect' => [
        'version' => '0.4.16',
    ],
    '@restart/hooks/useCommittedRef' => [
        'version' => '0.4.16',
    ],
    '@restart/hooks/useTimeout' => [
        'version' => '0.4.16',
    ],
    '@restart/ui/Dropdown' => [
        'version' => '1.9.2',
    ],
    '@restart/ui/DropdownItem' => [
        'version' => '1.9.2',
    ],
    '@restart/ui/DropdownMenu' => [
        'version' => '1.9.2',
    ],
    '@restart/hooks/useIsomorphicEffect' => [
        'version' => '0.4.16',
    ],
    'warning' => [
        'version' => '4.0.3',
    ],
    'invariant' => [
        'version' => '2.2.4',
    ],
    '@restart/ui/DropdownContext' => [
        'version' => '1.9.2',
    ],
    '@restart/ui/DropdownToggle' => [
        'version' => '1.9.2',
    ],
    '@restart/ui/Nav' => [
        'version' => '1.9.2',
    ],
    '@restart/ui/NavItem' => [
        'version' => '1.9.2',
    ],
    '@restart/ui/SelectableContext' => [
        'version' => '1.9.2',
    ],
    'dom-helpers/addEventListener' => [
        'version' => '5.2.1',
    ],
    'dom-helpers/canUseDOM' => [
        'version' => '5.2.1',
    ],
    'dom-helpers/ownerDocument' => [
        'version' => '5.2.1',
    ],
    'dom-helpers/removeEventListener' => [
        'version' => '5.2.1',
    ],
    'dom-helpers/scrollbarSize' => [
        'version' => '5.2.1',
    ],
    '@restart/hooks/useCallbackRef' => [
        'version' => '0.4.16',
    ],
    '@restart/hooks/useWillUnmount' => [
        'version' => '0.4.16',
    ],
    '@restart/ui/Modal' => [
        'version' => '1.9.2',
    ],
    'dom-helpers/addClass' => [
        'version' => '5.2.1',
    ],
    'dom-helpers/querySelectorAll' => [
        'version' => '5.2.1',
    ],
    'dom-helpers/removeClass' => [
        'version' => '5.2.1',
    ],
    '@restart/ui/ModalManager' => [
        'version' => '1.9.2',
    ],
    '@restart/hooks/useBreakpoint' => [
        'version' => '0.4.16',
    ],
    '@restart/ui/Overlay' => [
        'version' => '1.9.2',
    ],
    'dom-helpers/hasClass' => [
        'version' => '5.2.1',
    ],
    'dom-helpers/contains' => [
        'version' => '5.2.1',
    ],
    '@restart/ui/Tabs' => [
        'version' => '1.9.2',
    ],
    '@restart/ui/NoopTransition' => [
        'version' => '1.9.2',
    ],
    '@restart/ui/TabContext' => [
        'version' => '1.9.2',
    ],
    '@restart/ui/TabPanel' => [
        'version' => '1.9.2',
    ],
    '@restart/hooks' => [
        'version' => '0.5.0',
    ],
    '@react-aria/ssr' => [
        'version' => '3.9.7',
    ],
    '@babel/runtime/helpers/esm/extends' => [
        'version' => '7.23.9',
    ],
    '@babel/runtime/helpers/esm/objectWithoutPropertiesLoose' => [
        'version' => '7.23.9',
    ],
    '@babel/runtime/helpers/esm/inheritsLoose' => [
        'version' => '7.23.9',
    ],
    'react-lifecycles-compat' => [
        'version' => '3.0.4',
    ],
    '@restart/hooks/usePrevious' => [
        'version' => '0.5.0',
    ],
    '@restart/hooks/useForceUpdate' => [
        'version' => '0.5.0',
    ],
    '@restart/hooks/useEventListener' => [
        'version' => '0.5.0',
    ],
    'dequal' => [
        'version' => '2.0.3',
    ],
    '@restart/hooks/useSafeState' => [
        'version' => '0.5.0',
    ],
    '@popperjs/core/lib/modifiers/arrow' => [
        'version' => '2.11.8',
    ],
    '@popperjs/core/lib/modifiers/computeStyles' => [
        'version' => '2.11.8',
    ],
    '@popperjs/core/lib/modifiers/eventListeners' => [
        'version' => '2.11.8',
    ],
    '@popperjs/core/lib/modifiers/flip' => [
        'version' => '2.11.8',
    ],
    '@popperjs/core/lib/modifiers/hide' => [
        'version' => '2.11.8',
    ],
    '@popperjs/core/lib/modifiers/offset' => [
        'version' => '2.11.8',
    ],
    '@popperjs/core/lib/modifiers/popperOffsets' => [
        'version' => '2.11.8',
    ],
    '@popperjs/core/lib/modifiers/preventOverflow' => [
        'version' => '2.11.8',
    ],
    '@popperjs/core/lib/enums' => [
        'version' => '2.11.8',
    ],
    '@popperjs/core/lib/popper-base' => [
        'version' => '2.11.8',
    ],
    'dom-helpers/listen' => [
        'version' => '5.2.1',
    ],
    'dom-helpers/activeElement' => [
        'version' => '5.2.1',
    ],
    '@restart/hooks/useMounted' => [
        'version' => '0.5.0',
    ],
    '@fortawesome/react-fontawesome' => [
        'version' => '0.2.2',
    ],
    '@fortawesome/fontawesome-svg-core' => [
        'version' => '6.5.2',
    ],
    '@fortawesome/fontawesome-svg-core/styles.min.css' => [
        'version' => '6.5.2',
        'type' => 'css',
    ],
    '@fortawesome/free-solid-svg-icons' => [
        'version' => '6.7.2',
    ],
];
