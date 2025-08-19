export default function Hashtag({ hashtag }) {
    return (
        <a href={`/hashtag/${name}`} className="mt-4 block rounded-xl bg-gray-900 p-4 transition-colors hover:bg-gray-800">
            <p className="text-gray-400">#{hashtag.name}</p>
            <p className="text-sm text-gray-500">{hashtag.usage_count} posts</p>
        </a>
    );
}