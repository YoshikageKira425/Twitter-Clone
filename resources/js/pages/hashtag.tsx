import LeftBar from '@/components/side-nav/left-bar.jsx';
import NavBar from '@/components/side-nav/nav-bar.jsx';
import { type SharedData } from '@/types';
import { usePage } from '@inertiajs/react';
import Tweet from '@/components/tweets/tweet';

export default function Hashtag() {
    const { auth, hashtag, tweets } = usePage<SharedData>().props;
    console.log('Hashtag:', hashtag);
    console.log('Tweets:', tweets);

    return (
        <div className="flex h-screen bg-black text-gray-100">
            <div className="fixed h-full w-1/5 overflow-y-auto border-r border-gray-800 bg-black">
                <NavBar />
            </div>

            <div className="mr-[25%] ml-[20%] w-3/5 flex-grow border-r border-gray-800 bg-black">
                <div className="sticky top-0 z-10 border-b border-gray-800 bg-black/50 p-4 backdrop-blur-md">
                    <h2 className="text-xl font-bold">#{hashtag}</h2>
                </div>

                <div>
                    {tweets.map((tweet, index) => (
                        <Tweet key={index} tweet={tweet} />
                    ))}
                </div>
            </div>

            <div className="fixed top-0 right-0 h-full w-1/4 overflow-y-auto border-l border-gray-800 bg-black">
                <LeftBar />
            </div>
        </div>
    );
}
