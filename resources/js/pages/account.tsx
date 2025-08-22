import LeftBar from '@/components/side-nav/left-bar';
import NavBar from '@/components/side-nav/nav-bar.jsx';
import Comment from '@/components/tweets/comment';
import Tweet from '@/components/tweets/tweet';
import { type SharedData } from '@/types';
import { usePage } from '@inertiajs/react';
import axios from 'axios';
import { useState } from 'react';
import { FaRegUserCircle } from 'react-icons/fa';

export default function Account() {
    const { auth, user, tweets } = usePage<SharedData>().props;
    const [following, setFollowing] = useState(user.is_followed || false);
    const [showFollow, setShowFollow] = useState(false);
    const [content, setContent] = useState([]);
    console.log(tweets);

    const followClick = (content) => {
        setShowFollow(true);
        setContent(content);
    };

    const handleFollow = async () => {
        await axios.post(`/api/users/${user.id}/follow`);
        window.location.reload();
    };
    const handleUnfollow = async () => {
        await axios.post(`/api/users/${user.id}/follow`, { _method: 'DELETE' });
        window.location.reload();
    };

    return (
        <div className="flex h-screen bg-black text-gray-100">
            <div className="fixed h-full w-1/5 overflow-y-auto border-r border-gray-800 bg-black">
                <NavBar />
            </div>

            <div className="mr-[25%] ml-[20%] w-3/5 flex-grow border-r border-gray-800 bg-black">
                <div className="border-b border-gray-800 p-6">
                    <div className="flex items-center space-x-6">
                        <img className="h-24 w-24 rounded-full border-2 border-gray-700" src={user.profile_image} alt={`${user.name}'s avatar`} />
                        <div>
                            <h2 className="text-2xl font-bold">{user.name}</h2>
                            <div className="mb-3 flex gap-2 font-semibold text-neutral-600">
                                <button onClick={() => followClick(user.followers)} className="transition-colors duration-200 hover:text-blue-600">
                                    Followers {user.followers.length}
                                </button>
                                <p>•</p>
                                <button onClick={() => followClick(user.following)} className="transition-colors duration-200 hover:text-blue-600">
                                    Following {user.following.length}
                                </button>
                            </div>

                            {auth.user.id === user.id && (
                                <a
                                    href="/settings/profile"
                                    className="mt-2 rounded-full bg-blue-500 px-4 py-2 text-sm font-semibold text-white transition-colors duration-200 hover:bg-blue-600"
                                >
                                    Edit Profile
                                </a>
                            )}
                            {auth.user.id !== user.id &&
                                (following ? (
                                    <button
                                        onClick={handleUnfollow}
                                        className="mt-2 rounded-full border-2 border-black bg-blue-500 px-4 py-2 text-sm font-semibold text-white transition-colors duration-200 hover:border-blue-500 hover:bg-black hover:text-blue-500"
                                    >
                                        Unfollow
                                    </button>
                                ) : (
                                    <button
                                        onClick={handleFollow}
                                        className="mt-2 rounded-full bg-blue-500 px-4 py-2 text-sm font-semibold text-white transition-colors duration-200 hover:bg-blue-600"
                                    >
                                        Follow
                                    </button>
                                ))}
                        </div>
                    </div>
                    <div className="mt-6 text-gray-400">
                        <p className="mb-2">{user.bio || 'No bio yet.'}</p>
                        <p className="flex items-center space-x-2">
                            <FaRegUserCircle />
                            <span>Joined on {new Date(user.created_at).toLocaleDateString()}</span>
                        </p>
                    </div>
                </div>

                <div className="flex flex-row justify-between space-y-4 border-b border-gray-800">
                    <a
                        href={`/account/${user.name}`}
                        className="m-0 h-full w-full border-r border-gray-800 p-3 text-center transition-colors duration-200 hover:bg-neutral-900"
                    >
                        Tweets
                    </a>
                    <a
                        href={`/account/${user.name}/comments`}
                        className="m-0 h-full w-full border-r border-gray-800 p-3 text-center transition-colors duration-200 hover:bg-neutral-900"
                    >
                        Comments
                    </a>
                    <a
                        href={`/account/${user.name}/retweet`}
                        className="m-0 h-full w-full border-r border-gray-800 p-3 text-center transition-colors duration-200 hover:bg-neutral-900"
                    >
                        Retweet
                    </a>
                    {auth.user.id === user.id ? (
                        <>
                            <a
                                href={`/account/${user.name}/like`}
                                className="m-0 h-full w-full border-r border-gray-800 p-3 text-center transition-colors duration-200 hover:bg-neutral-900"
                            >
                                Like
                            </a>
                            <a
                                href={`/account/${user.name}/bookmark`}
                                className="m-0 h-full w-full p-3 text-center transition-colors duration-200 hover:bg-neutral-900"
                            >
                                Bookmarks
                            </a>
                        </>
                    ) : null}
                </div>

                <div>
                    {tweets.length > 0 ? (
                        tweets.map((tweet, index) =>
                            tweet.type === 'tweet' ? <Tweet key={index} tweet={tweet} /> : <Comment key={index} comment={tweet} />,
                        )
                    ) : (
                        <div className="p-4 text-gray-500">No tweets available.</div>
                    )}
                </div>
            </div>

            {showFollow && (
                <div onClick={() => setShowFollow(false)} className="bg-opacity-75 fixed inset-0 z-50 flex items-center justify-center bg-black/55">
                    <div className="w-1/3 rounded-lg bg-gray-800 p-6">
                        <h2 className="mb-4 text-xl font-bold">Followers</h2>
                        <ul className="space-y-4">
                            {content.map((follower) => (
                                <li key={follower.id} className="flex items-center space-x-4">
                                    <img className="h-10 w-10 rounded-full" src={follower.profile_image} alt={`${follower.name}'s avatar`} />
                                    <div>
                                        <p className="font-semibold">{follower.name}</p>
                                    </div>
                                </li>
                            ))}
                        </ul>
                    </div>
                </div>
            )}

            <div className="fixed top-0 right-0 h-full w-1/4 overflow-y-auto border-l border-gray-800 bg-black">
                <LeftBar />
            </div>
        </div>
    );
}
