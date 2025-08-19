import Hashtag from '@/components/side-nav/hashtag';
import NavBar from '@/components/side-nav/nav-bar.jsx';
import { type SharedData } from '@/types';
import { usePage } from '@inertiajs/react';
import axios from 'axios';
import { useEffect, useRef, useState } from 'react';

export default function Notification() {
    const [hashtags, setHashtags] = useState([]);
    const [search, setSearch] = useState('');
    const [users, setUsers] = useState([]);
    const [selectedUser, setSelectedUser] = useState([]);
    const [showResults, setShowResults] = useState(false); 
    const inputRef = useRef(null);

    useEffect(() => {
        axios
            .get('/api/hashtags')
            .then((response) => {
                setHashtags(response.data.hashtags);
            })
            .catch((error) => {
                console.error('Error fetching hashtags:', error);
            });

        axios
            .get('/api/selected-user')
            .then((response) => {
                setSelectedUser(response.data);
                console.log('Selected User:', response.data);
            }).catch((error) => {
                console.error('Error fetching selected user:', error);
            });
    }, []);

    useEffect(() => {
        if (search.trim() != '') {
            axios
                .get(`/api/users?search=${search}`)
                .then((response) => {
                    setUsers(response.data);
                })
                .catch((error) => {
                    console.error('Error fetching users:', error);
                });
        } else {
            setUsers([]);
        }
    }, [search]);

    const userButton = async (user) => {
        setShowResults(false);

        await axios.post('/api/selected-user', { id: user.id });
        window.location.href = `/account/${user.name}`;
    };

    const handleFocus = () => {
        setShowResults(true);
    };

    const handleBlur = () => {
        setTimeout(() => {
            setShowResults(false);
        }, 200);
    };

    return (
        <div className="flex h-screen bg-black text-gray-100">
            <div className="fixed h-full w-1/5 overflow-y-auto border-r border-gray-800 bg-black">
                <NavBar />
            </div>

            <div className="mr-[25%] ml-[20%] w-3/5 flex-grow border-r border-gray-800 bg-black">
                <div className="relative mt-4 px-3">
                    <span className="absolute inset-y-0 left-3 flex items-center pl-3">
                        <svg className="h-5 w-5 text-gray-400" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z"
                                stroke="currentColor"
                                strokeWidth="2"
                                strokeLinecap="round"
                                strokeLinejoin="round"
                            ></path>
                        </svg>
                    </span>

                    <input
                        ref={inputRef}
                        value={search}
                        onChange={(e) => setSearch(e.target.value)}
                        onFocus={handleFocus}
                        onBlur={handleBlur}
                        type="text"
                        className="focus:ring-opacity-40 w-full rounded-lg border bg-white py-2 pr-4 pl-10 text-gray-700 focus:border-blue-400 focus:ring focus:ring-blue-300 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:focus:border-blue-300"
                        placeholder="Search"
                    />
                </div>

                {search.trim() && showResults && (
                    <div className="fixed top-14 left-50 z-10 w-3/5">
                        <div className="flex max-h-80 w-[60%] translate-x-1/2 flex-col items-center overflow-auto rounded-b-md bg-gray-800 shadow-lg">
                            {users.length > 0 ? (
                                users.map((user, index) => (
                                    <button
                                        onClick={() => userButton(user)}
                                        key={index}
                                        className="flex w-full items-center gap-3 px-4 py-2 text-white transition hover:bg-gray-700"
                                    >
                                        <img src={user.profile_image} alt={user.name} className="h-10 w-10 rounded rounded-full object-cover" />
                                        <span className="truncate">{user.name}</span>
                                    </button>
                                ))
                            ) : (
                                <div className="px-4 py-2 text-neutral-400">Nothing found.</div>
                            )}
                        </div>
                    </div>
                )}

                <div className="p-4">
                    <h3 className="text-xl font-bold">What's happening</h3>
                    {hashtags.length > 0 &&
                        hashtags.map((hashtag, index) => <Hashtag key={index} hashtag={hashtag} />)}
                </div>
            </div>

            <div className="fixed top-0 right-0 h-full w-1/4 overflow-y-auto bg-black"></div>
        </div>
    );
}
