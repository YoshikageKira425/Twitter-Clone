export default function Tweet({ tweet }) {
    return (
        <>
            <div className="cursor-pointer border-b border-gray-800 p-4 transition-colors duration-200 hover:bg-gray-900">
                <div className="flex">
                    <img className="mr-4 h-10 w-10 rounded-full" src="https://via.placeholder.com/150" alt="Tweet user avatar" />
                    <div>
                        <div className="flex items-center">
                            <span className="font-bold">John Doe</span>
                            <span className="ml-2 text-gray-500">@johndoe</span>
                            <span className="ml-2 text-gray-500">· 1h</span>
                        </div>
                        <p className="mt-1">This is a sample tweet to demonstrate the layout. #twitterclone #tailwindcss</p>
                    </div>
                </div>
            </div>
        </>
    );
}
