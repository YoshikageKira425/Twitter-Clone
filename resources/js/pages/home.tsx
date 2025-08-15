import NavBar from '@/components/side-nav/nav-bar.jsx';
import Tweet from '@/components/tweets/tweet';
import { type SharedData } from '@/types';
import { usePage } from '@inertiajs/react';

export default function Home() {
    const { auth } = usePage<SharedData>().props;

    return (
        <div className="flex h-screen bg-black text-gray-100">
            <div className="w-1/5 border-r border-gray-800">
                <NavBar />
            </div>

            <div className="w-3/5 flex-2 border-r border-gray-800">
                <div className="sticky top-0 z-10 border-b border-gray-800 bg-black/50 p-4 backdrop-blur-md">
                    <h2 className="text-xl font-bold">Home</h2>
                </div>

                <div className="flex border-b border-gray-800 p-4">
                    <img className="mr-4 h-10 w-10 rounded-full" src="https://via.placeholder.com/150" alt="User avatar" />
                    <div className="flex-grow">
                        <textarea
                            className="h-12 w-full resize-none bg-transparent p-2 text-lg placeholder-gray-500 outline-none"
                            placeholder="What is happening?!"
                        />
                        <div className="mt-2 flex items-center justify-between">
                            <button className="rounded-full bg-blue-500 px-4 py-2 font-bold text-white hover:bg-blue-600">Post</button>
                        </div>
                    </div>
                </div>

                <Tweet tweet={null} />
            </div>

            <div className="w-1/4 border-l border-gray-800">
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
