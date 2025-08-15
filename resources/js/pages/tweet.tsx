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
