import NavBar from '@/components/side-nav/nav-bar.jsx';
import Tweet from '@/components/tweets/tweet';
import CommentSection from '@/components/tweets/commentSection';
import { type SharedData } from '@/types';
import { usePage } from '@inertiajs/react';

export default function Home() {
    const { auth, tweet } = usePage<SharedData>().props;

    return (
        <div className="flex h-screen bg-black text-gray-100">
            <div className="fixed h-full w-1/5 overflow-y-auto border-r bg-black border-gray-800">
                <NavBar />
            </div>

            <div className="mr-[25%] ml-[20%] w-3/5 flex-grow border-r bg-black border-gray-800">
                <Tweet tweet={tweet} />

                <div className="flex border-b border-gray-800 p-4">
                    <div className="flex w-full">
                        <textarea
                            className="h-12 w-full resize-none bg-transparent p-2 text-lg placeholder-gray-500 outline-none"
                            placeholder="Leave Comment!"
                        />
                        <button className="rounded-full bg-blue-500 px-4 py-2 font-bold text-white hover:bg-blue-600">Post</button>
                    </div>
                </div>

                <CommentSection />
            </div>

            <div className="fixed top-0 right-0 h-full w-1/4 overflow-y-auto border-l bg-black border-gray-800">
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
