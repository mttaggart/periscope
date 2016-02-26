json.array!(@schools) do |school|
  json.extract! school, :id
  json.url school_url(school, format: :json)
end
