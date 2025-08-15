import { usePage } from '@inertiajs/react';

export default function NavBar() {
    const { auth } = usePage().props;

    return (
        <>
            <div className="flex h-screen w-full flex-col p-4 text-white">
                <div className="mb-4">
                    <svg className="h-8 w-8 text-white" viewBox="0 0 24 24" aria-hidden="true">
                        <g>
                            <path
                                d="M18.244 2.25h3.308l-7.227 8.261L21.602 22H14.94l-5.21-6.174L4.99 22H1.68l7.73-8.835L1.254 2.25H8.08l4.714 5.976L18.244 2.25zm-.397 19.448l-5.918-6.908L4.67 3.1h2.17l5.672 6.617L19.528 21.698h-1.68z"
                                fill="currentColor"
                            ></path>
                        </g>
                    </svg>
                </div>

                <ul className="space-y-2">
                    <li>
                        <a href="/" className="flex items-center gap-4 rounded-full px-4 py-3 transition duration-200 hover:bg-neutral-800">
                            <svg className="h-6 w-6" viewBox="0 0 24 24" aria-hidden="true">
                                <g>
                                    <path
                                        d="M12 1.75l-9.5 9.5v9.75h5.5v-7h4v7h5.5v-9.75l-9.5-9.5zm0 2.872l6.5 6.5v8.378h-4.5v-7h-4v7h-4.5v-8.378l6.5-6.5z"
                                        fill="currentColor"
                                    ></path>
                                </g>
                            </svg>
                            <span className="text-xl font-medium">Home</span>
                        </a>
                    </li>
                    <li>
                        <a href="/" className="flex items-center gap-4 rounded-full px-4 py-3 transition duration-200 hover:bg-neutral-800">
                            <svg className="h-6 w-6" viewBox="0 0 24 24" aria-hidden="true">
                                <g>
                                    <path
                                        d="M10.25 3.75c-3.59 0-6.5 2.91-6.5 6.5s2.91 6.5 6.5 6.5c1.78 0 3.4-.72 4.59-1.89l4.3 4.3c.2.2.45.3.7.3s.5-.1.7-.3c.4-.4.4-1 0-1.4l-4.3-4.3c1.17-1.2 1.89-2.83 1.89-4.59 0-3.59-2.91-6.5-6.5-6.5zm-8 6.5c0-4.63 3.77-8.4 8.4-8.4s8.4 3.77 8.4 8.4-3.77 8.4-8.4 8.4-8.4-3.77-8.4-8.4z"
                                        fill="currentColor"
                                    ></path>
                                </g>
                            </svg>
                            <span className="text-xl font-medium">Explore</span>
                        </a>
                    </li>
                    <li>
                        <a href="/" className="flex items-center gap-4 rounded-full px-4 py-3 transition duration-200 hover:bg-neutral-800">
                            <svg className="h-6 w-6" viewBox="0 0 24 24" aria-hidden="true">
                                <g>
                                    <path
                                        d="M19.75 4h-15.5c-1.24 0-2.25 1.01-2.25 2.25v10.5c0 1.24 1.01 2.25 2.25 2.25h15.5c1.24 0 2.25-1.01 2.25-2.25V6.25c0-1.24-1.01-2.25-2.25-2.25zm-15.5 2c-.14 0-.25.11-.25.25v.23l7.63 7.64 7.64-7.63v-.24c0-.14-.11-.25-.25-.25h-15.5zm15.5 12h-15.5c-.14 0-.25-.11-.25-.25V8.71l7.64 7.64c.2.2.45.3.7.3s.5-.1.7-.3l7.64-7.64v8.54c0 .14-.11.25-.25.25z"
                                        fill="currentColor"
                                    ></path>
                                </g>
                            </svg>
                            <span className="text-xl font-medium">Notifications</span>
                        </a>
                    </li>
                    <li>
                        <a href="/" className="flex items-center gap-4 rounded-full px-4 py-3 transition duration-200 hover:bg-neutral-800">
                            <svg className="h-6 w-6" viewBox="0 0 24 24" aria-hidden="true">
                                <g>
                                    <path
                                        d="M12 11.75c-2.485 0-4.5 2.015-4.5 4.5s2.015 4.5 4.5 4.5c2.486 0 4.5-2.015 4.5-4.5s-2.014-4.5-4.5-4.5zm0-8.5c-2.485 0-4.5 2.015-4.5 4.5s2.015 4.5 4.5 4.5c2.486 0 4.5-2.015 4.5-4.5s-2.014-4.5-4.5-4.5z"
                                        fill="currentColor"
                                    ></path>
                                </g>
                            </svg>
                            <span className="text-xl font-medium">Profile</span>
                        </a>
                    </li>
                </ul>
            </div>
        </>
    );
}
