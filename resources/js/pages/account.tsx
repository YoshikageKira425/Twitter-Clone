import NavBar from '@/components/side-nav/nav-bar.jsx';
import Tweet from '@/components/tweets/tweet';
import { type SharedData } from '@/types';
import { usePage } from '@inertiajs/react';
import { FaRegUserCircle } from 'react-icons/fa';

export default function Account() {
    const { auth, user, tweets } = usePage<SharedData>().props;

    console.log(tweets);

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
                            {auth.user.id === user.id && (
                                <a href='/settings/profile' className="mt-2 rounded-full bg-blue-500 px-4 py-2 text-sm font-semibold text-white transition-colors duration-200 hover:bg-blue-600">
                                    Edit Profile
                                </a>
                            )}
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
                    <a href={`/account/${user.name}`} className="m-0 text-center h-full w-full border-r border-gray-800 p-3 transition-colors duration-200 hover:bg-neutral-900">Tweets</a>
                    <a href={`/account/${user.name}/comments`} className="m-0 text-center h-full w-full border-r border-gray-800 p-3 transition-colors duration-200 hover:bg-neutral-900">Comments</a>
                    <a href={`/account/${user.name}/retweet`} className="m-0 text-center h-full w-full p-3 transition-colors duration-200 hover:bg-neutral-900">Retweet</a>
                </div>

                <div>
                    {tweets.length > 0 ? (
                        tweets.map((tweet, index) => <Tweet key={index} tweet={tweet} />)
                    ) : (
                        <div className="p-4 text-gray-500">No tweets available.</div>
                    )}
                </div>
            </div>

            <div className="fixed top-0 right-0 h-full w-1/4 overflow-y-auto border-l border-gray-800 bg-black">
                <div className="p-4">
                    <h3 className="text-xl font-bold">What's happening</h3>
                    <div className="mt-4 rounded-xl bg-gray-900 p-4">
                        <p className="text-gray-400">#Topic1</p>
                        <p className="text-sm text-gray-500">2.5K posts</p>
                    </div>
                </div>
            </div>
        </div>
    );
}
