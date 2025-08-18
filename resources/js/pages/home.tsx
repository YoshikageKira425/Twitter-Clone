import NavBar from '@/components/side-nav/nav-bar.jsx';
import Tweet from '@/components/tweets/tweet';
import { type SharedData } from '@/types';
import { usePage } from '@inertiajs/react';
import axios from 'axios';
import { useRef, useState } from 'react';
import { FaPlus } from 'react-icons/fa';

export default function Home() {
    const { auth, tweets } = usePage<SharedData>().props;
    const [allTweets, setAllTweets] = useState(tweets);
    const [tweet, setTweet] = useState('');
    const tweetContainer = useRef(null);
    const [photo, setPhoto] = useState<string | null>(null);

    const handlePhotoUpload = (e) => {
        const file = e.target.files?.[0];
        if (file) {
            const reader = new FileReader();
            reader.onloadend = () => {
                setPhoto(reader.result as string);
            };
            reader.readAsDataURL(file);
        }
    };

    const postTweet = async () => {
        try {
            const formData = new FormData();
            if (tweet) {
                formData.append('content', tweet);
            }
            const file = document.getElementById('photo-upload')?.files[0];
            if (file) {
                formData.append('image', file);
            }

            const response = await axios.post('/api/posts', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });

            let newTweet = response.data.tweet;

            newTweet = {
                ...newTweet,
                is_liked_by_user: false,
                is_retweeted_by_user: false,
                is_bookmarked_by_user: false,
                bookmarks_count: 0,
                likes_count: 0,
                retweets_count: 0,
            };

            setAllTweets((prevTweets) => [newTweet, ...prevTweets]);

            setTweet('');
            alert(response.data.success);
        } catch (error) {
            console.error('Error posting tweet:', error);
            alert('There was error.');
        }
    };

    return (
        <div className="flex h-screen text-gray-100">
            <div className="fixed h-full w-1/5 overflow-y-auto border-r border-gray-800 bg-black">
                <NavBar />
            </div>

            <div className="mr-[25%] ml-[20%] w-3/5 flex-grow border-r border-gray-800 bg-black">
                <div className="sticky top-0 z-10 border-b border-gray-800 bg-black/50 p-4 backdrop-blur-md">
                    <h2 className="text-xl font-bold">Home</h2>
                </div>

                <div className="flex border-b border-gray-800 p-4">
                    <img
                        className="mr-4 h-10 w-10 rounded-full"
                        src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAKkAAACUCAMAAADf7/luAAAAYFBMVEXZ3OFwd3/c3+Ryd3xydn9weHtweH1scXXh5OnZ3eBtdHxrdXfQ09hvdHjV2N1sdHrHytC+wsZ/hom2ur+Wm5+HjJBmbXSPkpewtbmfpKh7gIWprrJlbnDJztGNlZmFi5S+luYFAAAE4klEQVR4nO2c2ZajIBBAtVBxAfcVNf3/fzmYTKY73VlAMGXP8Z4z89QP94AURVHEcQ4ODg4ODg4ODg4ODg4ODg5eAAuh/Ict8hwInbwah2EYq9wJdysLkFaN68ZxlmVx7Lpdle5yZMGZB95KwX/ElNe5szvXcK4JJe4NhFO/nkNstRsAejd27xG7454+AZi79q7nMrC0m3ejCnmZ+A9NCS3znahGBbk/81cyUuxCFQrOn4rKlcX3oAo54eSFqfwL/A8AWPl86i/wEn1ZRU2iICojQIMsGo5toGLquu2IugXAnKl5ShLc+e80TDtET6jIi2X/Bd/HDFXNqwD11TTBW1RQtOqickwntEENTyqh9JPshLT8gb3YRX/OP8MZ1HBUX/hnAj7imMJJaXv6hPAOxVTu+NqmJcr0Q0Eeps+PVHESVei/n/BeE1copsMK0wFB1InqFaa1EyGonjS20qvpKcIw1dn0r6ZN+itMPS9uUGb/PzdNEUSdbsV3ipP3r4tSCKyI/IQOGJ8pjLqmvk9HBNEV+77vJz1OLsU1cynP83ByqVxomwqcQlqqVpL6avqBEk6dsNY0DZIa6RzVa5uiLCiZoP6aU7QTfmhWJhqswiRUD+927tKinKLOpEJj/gkXOCt/IdLZUAlFqqAsACvVCz5IZYmrak991Y2KIoWov4Snx/eQt8Qd7qU0MKEW/mOBOfdn1cJXCap+gH8fGRYPbvZvRnQXd7xQiOzZFQohQSb2ICqj6lzSx6pkueBHvzT9C4SjoA9NuRh31I8E8xC0WfAVKSn/zyYx7GVALwCwsZymxP+HG8fJNJUj22EfErC+bkpxERVCfNQ9g331IF0BgIjlRVFUVZHnLNpT+9FP4BNslYOD/4ToDLbFa2So2rvpEpgYY3N+hrHU2WOsArlDFf1QN40Q3rn+WDaneugLtidZ6ZLmQ+n7yULsSVOpyjlPljSgHIp0H7bgzNUpbuNHiX8ct6SuZnRXgKoWSeKdB/I+nkcoFUPlYCYrEPXiPMUvTAmRfyV6tPxPpqT+pF5D8Scx4jR3RH1DfV+nW47TBmFcIW/O864s6i75dcKbHN66JwAbqGZB+gLhdHjnJxAWzZNz81NTQqbmbYd/cEY3I+tMperybOI9XsukXfskKCkQTF36BlWYyyQwNPWTZvMiQAS5QsHsNfHmDf5QrFvzP9j62cTSGL1uJX2H8E0LlVBwG1N/dd1OFYpJtzvymSmhW32rMItM8UGEmirf6LofUo3LJzWSZpO4Cl1ic0QXfLpFO3I4Uosf6YXAzezfUELhW4pPtyS2AwCwkm9javs2NRweXjmYEbS11ZOg3EStf6RXWqvzn67o31Uls9npCb2dtOQuVjt+mLAd829MBbMlGg6qzwrXqVp7jAiOWYr/Cs8jls5VMG5taquXhpVbm8Z2wj9Umu17+gTcTs+X7oOtFfCTDVGm32KuT5ubi+o3bq8ytbCmwmCTHOo7xDikwqzXD7mWybioEo72zs3PSIz3qUjzne5qU9PVr/jjDOYYd1LK49NbRM0feGr3l682NX6JPLzLlBo+8EzfsJVeTHlndkhh4h1hfyE2zPxn7bfP61WNtn7IdX6fwYzWqPCn+7bAyNQoRw3HN5oa7afqP3ZjDjUzHbar8nwnHn6NqVEpLaz3Y/oH0JxL9ikte0gAAAAASUVORK5CYII="
                        alt="User avatar"
                    />
                    <div className="flex-grow">
                        <textarea
                            value={tweet}
                            onChange={(e) => setTweet(e.target.value)}
                            className="h-12 w-full resize-none bg-transparent p-2 text-lg placeholder-gray-500 outline-none"
                            placeholder="What is happening?!"
                        />

                        {photo && (
                            <div className="mt-2">
                                <img src={photo} alt="preview" className="max-h-60 rounded-lg" />
                            </div>
                        )}

                        <div className="mt-2 flex items-center gap-3">
                            <button
                                onClick={postTweet}
                                className="rounded-full bg-blue-500 px-4 py-2 font-bold text-white transition-colors duration-200 hover:bg-blue-600"
                            >
                                Post
                            </button>

                            <label
                                htmlFor="photo-upload"
                                className="cursor-pointer rounded-full p-3 text-center text-blue-500 transition-colors duration-200 hover:bg-blue-600 hover:text-white"
                            >
                                <FaPlus />
                                <input
                                    id="photo-upload"
                                    name="photo-upload"
                                    type="file"
                                    hidden
                                    id="photo-upload"
                                    name="photo-upload"
                                    type="file"
                                    accept="image/*"
                                    hidden
                                    onChange={handlePhotoUpload}
                                />
                            </label>
                        </div>
                    </div>
                </div>

                <div ref={tweetContainer}>
                    {allTweets.map((tweet, index) => (
                        <Tweet key={index} tweet={tweet} />
                    ))}
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
