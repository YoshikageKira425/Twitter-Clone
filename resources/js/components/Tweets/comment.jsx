import { FaRegComment, FaRegHeart, FaRetweet, FaRegBookmark } from 'react-icons/fa';

export default function Comment() {
    // const formatTimestamp = (date) => {
    //     const now = new Date();
    //     const commentDate = new Date(date);
    //     const diffInMinutes = Math.floor((now - commentDate) / (1000 * 60));

    //     if (diffInMinutes < 60) {
    //         return `${diffInMinutes}m`;
    //     }
    //     const diffInHours = Math.floor(diffInMinutes / 60);
    //     if (diffInHours < 24) {
    //         return `${diffInHours}h`;
    //     }
    //     const diffInDays = Math.floor(diffInHours / 24);
    //     return `${diffInDays}d`;
    // };

    return (
        <div className="flex border-b border-gray-800 p-4 transition-colors duration-200 hover:bg-neutral-900">
            <img className="mr-3 h-10 w-10 rounded-full" src={'https://via.placeholder.com/150'} alt="User avatar" />

            <div className="flex-grow">
                <div className="flex items-center justify-between">
                    <div className="flex items-center">
                        <span className="font-bold text-white">teset</span>
                        <span className="mx-1 text-gray-500">·</span>
                        <span className="text-gray-500">test</span>
                    </div>
                </div>

                <p className="mt-1 text-white">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Eos, recusandae.</p>

                <div className="mt-3 flex space-x-12 text-gray-500">
                    <button className="flex cursor-pointer items-center space-x-2 hover:text-blue-500">
                        <FaRegComment className="h-4 w-4" />
                        <span className="text-sm">0</span>
                    </button>
                    <button className="flex cursor-pointer items-center space-x-2 hover:text-green-500">
                        <FaRetweet className="h-4 w-4" />
                        <span className="text-sm">0</span>
                    </button>
                    <button className="flex cursor-pointer items-center space-x-2 hover:text-red-500">
                        <FaRegHeart className="h-4 w-4" />
                        <span className="text-sm">0</span>
                    </button>
                    <button className="flex cursor-pointer items-center space-x-2 hover:text-neutral-600">
                        <FaRegBookmark className="h-4 w-4" />
                    </button>
                </div>
            </div>
        </div>
    );
}
